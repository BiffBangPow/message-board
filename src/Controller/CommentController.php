<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\CommentFormHandler;
use BiffBangPow\MessageBoard\Services\SessionService;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityRepository
     */
    private $commentRepository;
    /**
     * @var CommentFormHandler
     */
    private $commentFormHandler;
    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $commentRepository
     * @param CommentFormHandler $commentFormHandler
     * @param SessionService $sessionService
     * @internal param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $commentRepository, CommentFormHandler $commentFormHandler, SessionService $sessionService)
    {
        $this->twig = $twig;
        $this->commentRepository = $commentRepository;
        $this->commentFormHandler = $commentFormHandler;
        $this->sessionService = $sessionService;
    }

    /**
     * @param Request $request
     * @param int   $id
     * @return RedirectResponse
     */
    public function newCommentAction(Request $request, int $id)
    {
        $this->commentFormHandler->handle($request, $id);

        return new RedirectResponse('/thread/'.$id);
    }

}