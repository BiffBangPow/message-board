<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use BiffBangPow\MessageBoard\Model\Thread;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BiffBangPow\MessageBoard\Services\SessionService;

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
     * @var SessionService
     */
    private $sessionService;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     * @param ThreadFormHandler $threadFormHandler
     * @param SessionService $sessionService
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $threadRepository, ThreadFormHandler $threadFormHandler, SessionService $sessionService)
    {
        $this->twig = $twig;
        $this->threadRepository = $threadRepository;
        $this->threadFormHandler = $threadFormHandler;
        $this->sessionService = $sessionService;
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