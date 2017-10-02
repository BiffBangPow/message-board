<?php

namespace BiffBangPow\MessageBoard;

use BiffBangPow\MessageBoard\Controller\MainController;
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
        $this->application->get('/', function(Request $request) use ($mainController) {
            return $mainController->indexAction($request);
        });

        $this->application->get('/thread/{id}', function(Request $request, int $id) use ($mainController) {
            return $mainController->threadAction($request, $id);
        });

        return $this;
    }
}
