<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Model\Comment;
use BiffBangPow\MessageBoard\Services\SessionService;

class CommentFormHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EntityRepository
     */
    private $threadRepository;
    /**
     * @var SessionService
     */
    private $sessionService;
    /**
     * @var EntityRepository
     */
    private $userRepository;

    /**
     * CommentFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository $threadRepository
     * @param SessionService $sessionService
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EntityRepository $threadRepository, SessionService $sessionService, EntityRepository $userRepository)
    {

        $this->entityManager = $entityManager;
        $this->threadRepository = $threadRepository;
        $this->sessionService = $sessionService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param int $id
     */
    public function handle(Request $request, int $id)
    {
        $user = $this->userRepository->find($this->sessionService->getUserId());

        $comment = new Comment();
        $thread = $this->threadRepository->find($id);

        $comment->setContent($request->get('content'));
        $comment->setThread($thread);
        $comment->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}