<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\Services\SessionService;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityRepository
     */
    private $threadRepository;

    /**
     * @var EntityRepository
     */
    private $commentRepository;

    /**
     * @var SessionServicex
     */
    private $sessionService;

    /**
     * Controller constructor
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     * @param EntityRepository $commentRepository
     * @param SessionService $sessionService
     */
    public function __construct(\Twig_Environment $twig, $threadRepository, $commentRepository, $userRepository ,$sessionService)
    {
        $this->twig = $twig;
        $this->threadRepository = $threadRepository;
        $this->commentRepository = $commentRepository;
        $this->sessionService = $sessionService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $numberOfResultsPerPage = 10;
        $currentPage = $request->get('page', 1);

        $totalCount = $this->threadRepository->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $threads = $this->threadRepository
            ->createQueryBuilder('t')
            ->setFirstResult(($currentPage-1)*$numberOfResultsPerPage)
            ->setMaxResults($numberOfResultsPerPage)
            ->getQuery()
            ->execute()
            ;

        $totalPages = ceil($totalCount/ $numberOfResultsPerPage);

        $content = $this->twig->render('index.html.twig', [
            'threads' => $threads,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);

        return new Response($content);
    }

    /**
     * @param Request $request
     * @param int   $id
     * @return Response
     */
    public function threadAction(Request $request, int $id)
    {
        $currentPage = $request->get('page', 1);
        $numberOfResultsPerPage = 10;

        $comments = $this->commentRepository
            ->createQueryBuilder('c')
            ->where('c.thread = ?1')
            ->setParameter(1, $id)
            ->setFirstResult(($currentPage - 1) * $numberOfResultsPerPage)
            ->setMaxResults($numberOfResultsPerPage)
            ->getQuery()
            ->execute();

        $totalCount = $this->commentRepository->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.thread = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($totalCount / $numberOfResultsPerPage);

        $thread = $this->threadRepository->find($id);

        return new Response($this->twig->render('thread.html.twig', [
            'thread' => $thread,
            'comments' => $comments,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]));
    }
}
