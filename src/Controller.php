<?php


namespace BiffBangPow\MessageBoard;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function indexAction(Request $request)
    {
        $response = new Response();
        try {
            $response->setContent($this->twig->render('index.html.twig', [
                'ip_address' => $request->getClientIp()
            ]));
        } catch (\Exception $ex) {
            $response->setContent($ex);
        }
        return $response;
    }
}
