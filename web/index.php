<?php

use BiffBangPow\MessageBoard\ApplicationBuilder;
use BiffBangPow\MessageBoard\Controller;

require_once "../vendor/autoload.php";

$loader = new Twig_Loader_Filesystem("../src/View/.");
$twig = new Twig_Environment($loader, [
    'cache' => '/tmp/twig_cache'
]);

$controller = new Controller($twig);
$applicationBuilder = new ApplicationBuilder($controller);
$app = $applicationBuilder->buildApplication();
$app->run();