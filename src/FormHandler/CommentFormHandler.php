<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Model\Comment;

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
     * CommentFormHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository $threadRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EntityRepository $threadRepository)
    {

        $this->entityManager = $entityManager;
        $this->threadRepository = $threadRepository;
    }

    /**
     * @param Request $request
     * @param int $id
     */
    public function handle(Request $request, int $id){
        $comment = new Comment();
        $thread = $this ->threadRepository->find($id);

        $comment -> setContent((htmlspecialchars($request->get('content'))));
        $comment ->setThread($thread);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}