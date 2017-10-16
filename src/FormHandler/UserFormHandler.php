<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Model\User;
use BiffBangPow\MessageBoard\Services\PasswordEncryptionService;
use BiffBangPow\MessageBoard\Services\SessionService;

class UserFormHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SessionService
     */
    private $sessionService;
    /**
     * @var PasswordEncryptionService
     */
    private $passwordEncryptionService;
    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * UserFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository $userRepository
     * @param SessionService $sessionService
     * @param PasswordEncryptionService $passwordEncryptionService
     * @internal param EntityRepository $entityRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EntityRepository $userRepository, SessionService $sessionService, PasswordEncryptionService $passwordEncryptionService)
    {
        $this->entityManager = $entityManager;
        $this->sessionService = $sessionService;
        $this->passwordEncryptionService = $passwordEncryptionService;
        $this->userRepository = $userRepository;
    }

    public function handleRegistration(Request $request)
    {
        $user = new User();

        $username = ($request->get('username'));
        $passwordInput = ($request->get('password'));

        $user->setUsername($username);
        $user = $this->passwordEncryptionService->encryptPassword($user, $passwordInput);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->sessionService->setUserId($user->getId());
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function handleLogin(Request $request)
    {
        $username = ($request->get('username'));
        $passwordInput = ($request->get('password'));

        $userArray = $this->userRepository
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->execute()
        ;

        $user = $userArray[0];

        $passwordInputConcatenated = $passwordInput.$user->getSalt();

        $result = password_verify($passwordInputConcatenated, $user->getPassword());

        if ($result) {
            $userId = $user->getId();
            $this->sessionService->setUserId($userId);
        }

        return $result;
    }
}
