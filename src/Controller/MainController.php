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
        $response = new Response();
        $threads = $this->threadRepository->findAll();
        $currentPage = $request->get('page', 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;
        $totalItems = count($threads);
        $totalPages = ceil($totalItems / $limit);
        $itemsList = array_splice($threads, $offset, $limit);

        $content = $this->twig->render('index.html.twig', [
            'threads' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
        $response->setContent($content);
        return $response;
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
