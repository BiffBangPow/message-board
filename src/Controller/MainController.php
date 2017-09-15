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
        $content = $this->twig->render('index.html.twig', [
            'threads' => $threads
        ]);
        $response->setContent($content);
        return $response;
    }
}
