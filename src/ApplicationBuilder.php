<?php


namespace BiffBangPow\MessageBoard;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ApplicationBuilder
{

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Builds the application with configured routing
     * @return Application
     */
    public function buildApplication()
    {
        $app = new Application();

        $app->get('/', function(Request $request) use($app) {
            return $this->controller->indexAction($request);
        });

        return $app;
    }
}