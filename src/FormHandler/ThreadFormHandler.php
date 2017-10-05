<?php

namespace BiffBangPow\MessageBoard\FormHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Model\Thread;

class ThreadFormHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(Request $request)
    {
        $thread = new Thread();

        $thread->setTitle((htmlspecialchars($request->get('title'))));
        $thread->setContent((htmlspecialchars($request->get('content'))));

        $this->entityManager->persist($thread);
        $this->entityManager->flush();
    }
}