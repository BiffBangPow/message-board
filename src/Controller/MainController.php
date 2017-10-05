<?php


namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\Model\Thread;
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
     * @var EntityRepository
     */
    private $commentRepository;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $threadRepository, EntityRepository $commentRepository)
    {
        $this->twig = $twig;
        $this->threadRepository = $threadRepository;
        $this->commentRepository = $commentRepository;
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
            ->execute()
        ;

  /**      foreach ($threads as $thread){
            var_dump($thread);
        }
*/
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

        $qb = $this->commentRepository->createQueryBuilder('c');
        $qb->select('c.content, c.postedAt')
            ->where('c.thread = ?1')
            ->setParameter(1, $id)
            ->setFirstResult(($currentPage-1)*$numberOfResultsPerPage)
            ->setMaxResults($numberOfResultsPerPage)
        ->getQuery();

        $comments = $qb->getQuery()->getResult();

        $totalCount = $this->commentRepository->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.thread = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($totalCount/ $numberOfResultsPerPage);

        $thread = $this->threadRepository->find($id);

        return new Response($this->twig->render('thread.html.twig', [
          'thread' => $thread,
          'comments' => $comments,
          'currentPage'  => $currentPage,
          'totalPages' => $totalPages
      ]));
    }
}
