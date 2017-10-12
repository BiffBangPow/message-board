<?php

namespace BiffBangPow\MessageBoard;

use BiffBangPow\MessageBoard\Controller\CommentController;
use BiffBangPow\MessageBoard\Controller\MainController;
use BiffBangPow\MessageBoard\Controller\ThreadController;
use BiffBangPow\MessageBoard\Controller\UserController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Router
{

    /**
     * @var Application
     */
    private $application;

    /**
     * ApplicationConfigurator constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param MainController $mainController
     * @return $this
     */
    public function routeMainController(MainController $mainController)
    {
        $this->application->get('/', function (Request $request) use ($mainController) {
            return $mainController->indexAction($request);
        });

        $this->application->get('/thread/{id}', function (Request $request, int $id) use ($mainController) {
            return $mainController->threadAction($request, $id);
        });

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
        });

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
        });

        $this->application->post('/threads/new', function (Request $request) use ($threadController) {
            return $threadController->createNewThreadAction($request);
        });

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
        });

        $this->application->post('/user/login', function (Request $request) use ($userController) {
            return $userController->loginAction($request);
        });

        $this->application->get('/user/register', function (Request $request) use ($userController) {
            return $userController->getRegisterAction($request);
        });

        $this->application->post('/user/register', function (Request $request) use ($userController) {
            return $userController->registerAction($request);
        });

        return $this;
    }
}
