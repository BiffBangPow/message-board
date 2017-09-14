<?php


namespace BiffBangPow\MessageBoard\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $response = new Response();
        $content = $this->twig->render('index.html.twig', [
            'ip_address' => $request->getClientIp()
        ]);
        $response->setContent($content);
        return $response;
    }
}
