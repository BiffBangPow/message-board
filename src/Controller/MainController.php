<?php

namespace BiffBangPow\MessageBoard\Controller;

use Doctrine\ORM\EntityRepository;
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
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $threadRepository)
    {
        $this->twig = $twig;
        $this->threadRepository = $threadRepository;
    }

    /**
     * @param Request $request
     * @return Response
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
            ->execute();
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
        $thread = $this->threadRepository->find($id);
        return new Response($this->twig->render('thread.html.twig', [
          'thread' => $thread
        ]));
    }
}
