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

        return $this;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Router
     */
    public static function buildApplication()
    {
        $app = new Application();
        return new self($app);
    }
}