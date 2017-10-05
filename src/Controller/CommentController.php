<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\CommentFormHandler;
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
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $commentRepository, CommentFormHandler $commentFormHandler)
    {
        $this->twig = $twig;
        $this->commentRepository = $commentRepository;
        $this->commentFormHandler = $commentFormHandler;
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