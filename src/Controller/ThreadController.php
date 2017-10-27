<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ThreadFormHandler
     */
    private $threadFormHandler;


    /**
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     * @param ThreadFormHandler $threadFormHandler
     */
    public function __construct(
        \Twig_Environment $twig,
        EntityRepository $threadRepository,
        ThreadFormHandler $threadFormHandler
    )
    {
        $this->twig = $twig;
        $this->threadFormHandler = $threadFormHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newThreadAction(Request $request)
    {
        return new Response($this->twig->render('createThreadForm.html.twig', []));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function createNewThreadAction(Request $request)
    {
        $this->threadFormHandler->handle($request);

        return new RedirectResponse('/');
    }
}