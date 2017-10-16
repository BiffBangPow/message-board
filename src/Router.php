<?php

namespace BiffBangPow\MessageBoard;

use BiffBangPow\MessageBoard\Controller\CommentController;
use BiffBangPow\MessageBoard\Controller\MainController;
use BiffBangPow\MessageBoard\Controller\ThreadController;
use BiffBangPow\MessageBoard\Controller\UserController;
use BiffBangPow\MessageBoard\Services\SessionService;
use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Router
{

    /**
     * @var Application
     */
    private $application;
    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * ApplicationConfigurator constructor.
     * @param Application $application
     * @param SessionService $sessionService
     */
    public function __construct(Application $application, SessionService $sessionService)
    {
        $this->application = $application;

        $this->sessionService = $sessionService;
    }


    /**
     * @param MainController $mainController
     * @return $this
     */
    public function routeMainController(MainController $mainController)
    {
        $this->application->get('/', function (Request $request) use ($mainController) {
            return $mainController->indexAction($request);
        })
        ->before($this->checkSessionMissing());

        $this->application->get('/thread/{id}', function (Request $request, int $id) use ($mainController) {
            return $mainController->threadAction($request, $id);
        })->before($this->checkSessionMissing());

        return $this;
    }

    /**
     * @param CommentController $commentController
     * @return $this
     */
    public function routeCommentController(CommentController $commentController)
    {
        $this->application->post('/threads/{id}/comments/new', function (Request $request, int $id) use ($commentController) {
            return $commentController->newCommentAction($request, $id);
        })->before($this->checkSessionMissing());

        $this->application->get('comments/{id}/report', function (Request $request, int $id) use ($commentController) {
            return $commentController->reportCommentAction($request, $id);
        });

        $this->application->post('comments/{id}/report', function (Request $request, int $id) use ($commentController) {
            return $commentController->createCommentReportAction($request, $id);
        });
        return $this;
    }

    /**
     * @param ThreadController $threadController
     * @return $this
     */
    public function routeThreadController(ThreadController $threadController)
    {
        $this->application->get('/threads/new', function (Request $request) use ($threadController) {
            return $threadController->newThreadAction($request);
        })->before($this->checkSessionMissing());

        $this->application->post('/threads/new', function (Request $request) use ($threadController) {
            return $threadController->createNewThreadAction($request);
        })->before($this->checkSessionMissing());

        return $this;
    }

    /**
     * @param UserController $userController
     * @return $this
     */
    public function routeUserController(UserController $userController)
    {
        $this->application->get('/user/login', function (Request $request) use ($userController) {
            return $userController->getLoginAction($request);
        })->before($this->checkSessionExisting());

        $this->application->post('/user/login', function (Request $request) use ($userController) {
            return $userController->loginAction($request);
        })->before($this->checkSessionExisting());

        $this->application->get('/user/register', function (Request $request) use ($userController) {
            return $userController->getRegisterAction($request);
        })->before($this->checkSessionExisting());

        $this->application->post('/user/register', function (Request $request) use ($userController) {
            return $userController->registerAction($request);
        })->before($this->checkSessionExisting());

        return $this;
    }

    /**
     * @return \Closure
     */
    public function checkSessionMissing(): \Closure
    {
        return function () {
            if (!$this->sessionService->getIsLoggedIn()) {
                return new RedirectResponse('/user/login');
            }
        };
    }

    /**
     * @return \Closure
     */
    public function checkSessionExisting(): \Closure
    {
        return function () {
            if ($this->sessionService->getIsLoggedIn()) {
                return new RedirectResponse('/');
            }
        };
    }
}
