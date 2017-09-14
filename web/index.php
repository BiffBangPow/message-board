<?php

use BiffBangPow\MessageBoard\Router;
use BiffBangPow\MessageBoard\Controller\MainController;

require_once "../vendor/autoload.php";

$loader = new Twig_Loader_Filesystem("../src/View");
$twig = new Twig_Environment($loader, [
    'cache' => '/tmp/twig_cache'
]);

$mainController = new MainController($twig);

$application = Router::buildApplication()
    ->routeMainController($mainController)
    ->getApplication();

$application->run();
