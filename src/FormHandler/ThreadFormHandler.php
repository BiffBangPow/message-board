<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Services\SessionService;

class ThreadFormHandler
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
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * ThreadFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param SessionService $sessionService
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SessionService $sessionService, EntityRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->sessionService = $sessionService;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request)
    {
        $user = $this->userRepository->find($this->sessionService->getUserId());

        $thread = new Thread();

        $thread->setTitle(($request->get('title')));
        $thread->setContent($request->get('content'));
        $thread->setUser($user);

        $this->entityManager->persist($thread);
        $this->entityManager->flush();
    }
}