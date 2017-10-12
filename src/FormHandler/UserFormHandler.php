<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
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
     * UserFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param SessionService $sessionService
     */
    public function __construct(EntityManagerInterface $entityManager, SessionService $sessionService)
    {
        $this->entityManager = $entityManager;
        $this->sessionService = $sessionService;
    }

    public function handleRegistration(Request $request)
    {
        $passwordEncryptionServices = new PasswordEncryptionService();
        $user = new User();

        $username = ($request->get('username'));
        $passwordInput = ($request->get('password'));

        $user->setUsername($username);
        $user = $passwordEncryptionServices->encryptPassword($user, $passwordInput);

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

        $passwordDatabase =  $this->entityManager->createQuery('Select u.password from BiffBangPow\MessageBoard\Model\USER  u WHERE u.username = ?1')
            ->setParameter(1, $username)
            ->execute()
        ;

        $saltArray=  $this->entityManager->createQuery('Select u.salt from BiffBangPow\MessageBoard\Model\USER u WHERE u.username = ?1')
            ->setParameter(1, $username)
            ->execute()
        ;

        $userIdArray= $this->entityManager->createQuery('Select u.id from BiffBangPow\MessageBoard\Model\User u WHERE u.username =?1')
            ->setParameter(1, $username)
            ->execute();

        reset($saltArray);
        reset($passwordDatabase);
        reset($userIdArray);

        $userId = current(array_slice($userIdArray, 0, 1)[0]);

        $this->sessionService->setUserId($userId);

        $salt = current(array_slice($saltArray, 0, 1)[0]);

        $passwordInputConcatenated = $passwordInput.$salt;

        $passwordOnDatabase = current(array_slice($passwordDatabase, 0, 1)[0]);

        $result = password_verify($passwordInputConcatenated, $passwordOnDatabase);

        return $result;
    }
}
