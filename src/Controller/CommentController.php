<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\CommentFormHandler;
use BiffBangPow\MessageBoard\Services\SessionService;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var EntityRepository
     */
    private $reportRepository;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $commentRepository
     * @param EntityRepository $reportRepository
     * @param CommentFormHandler $commentFormHandler
     * @param SessionService $sessionService
     * @internal param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $commentRepository, EntityRepository $reportRepository, CommentFormHandler $commentFormHandler, SessionService $sessionService)
    {
        $this->twig = $twig;
        $this->commentRepository = $commentRepository;
        $this->commentFormHandler = $commentFormHandler;
        $this->sessionService = $sessionService;
        $this->reportRepository = $reportRepository;
    }

    /**
     * @param Request $request
     * @param int   $id
     * @return RedirectResponse
     */
    public function newCommentAction(Request $request, int $id)
    {
        $this->commentFormHandler->handleNewComment($request, $id);

        return new RedirectResponse('/thread/'.$id);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function reportCommentAction(Request $request, int $id)
    {
        if ($this->sessionService->getIsLoggedIn()) {
            return new Response($content = $this->twig->render('report.html.twig', [
                'commentId' => $id
            ]));
        } else {
            return new RedirectResponse('/');
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function createCommentReportAction(Request $request, int $id)
    {
        $reportsByUser  = $this->reportRepository
            ->createQueryBuilder('r')
            ->where('r.user = ?1')
            ->setParameter(1, $this->sessionService->getUserId())
            ->getQuery()
            ->execute()
        ;

        foreach ($reportsByUser as $reportByUser) {
            if ($reportByUser->getComment()->getId() == $id) {
                echo ('You have already reported this comment');
                return new RedirectResponse('/');
            }
        }

        $this->commentFormHandler->handleReport($request, $id);
        return new RedirectResponse('/');
    }

}