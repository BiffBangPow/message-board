<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use BiffBangPow\MessageBoard\Model\Thread;
use Doctrine\ORM\EntityManager;
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
     * @var EntityRepository
     */
    private $threadRepository;

    /**
     * @var ThreadFormHandler
     */
    private $threadFormHandler;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $threadRepository, ThreadFormHandler $threadFormHandler)
    {
        $this->twig = $twig;
        $this->threadRepository = $threadRepository;
        $this->threadFormHandler = $threadFormHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newThreadAction(Request $request){
        return new Response($this->twig->render('createThreadForm.html.twig', []));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function createNewThreadAction(Request $request){
        $this->threadFormHandler->handle($request);

        return new RedirectResponse('/');
    }
}