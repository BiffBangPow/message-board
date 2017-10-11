<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use BiffBangPow\MessageBoard\Model\Report;
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
     * @var EntityRepository
     */
    private $commentRepository;

    /**
     * CommentFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository $threadRepository
     * @param SessionService $sessionService
     * @param EntityRepository $userRepository
     * @param EntityRepository $commentRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EntityRepository $threadRepository, SessionService $sessionService, EntityRepository $userRepository, EntityRepository $commentRepository)
    {

        $this->entityManager = $entityManager;
        $this->threadRepository = $threadRepository;
        $this->sessionService = $sessionService;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Request $request
     * @param int $id
     */
    public function handleNewComment(Request $request, int $id)
    {
        $user = $this->userRepository->find($this->sessionService->getUserId());

        $comment = new Comment();
        $thread = $this ->threadRepository->find($id);

        $comment->setContent($request->get('content'));
        $comment->setThread($thread);
        $comment->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function handleReport(Request $request, int $id)
    {
        $user = $this->userRepository->find($this->sessionService->getUserId());
        $comment = $this->commentRepository->find($id);
        $report = new Report();

        $report->setUser($user);
        $report->setComment($comment);
        $report->setComplaint($request->get('complaint'));

        $this->entityManager->persist($report);
        $this->entityManager->flush();
    }
}