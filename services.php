<?php

use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Router;
use BiffBangPow\MessageBoard\Controller\MainController;
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/vendor/autoload.php";

//Doctrine ORM
$entityConfiguration = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src/Model"], false);
$entityManager = \Doctrine\ORM\EntityManager::create([
    'dbname' => 'message-board',
    'user' => 'message-board',
    'password' => 'pfj95khzMwVWDPgv',
    'host' => 'mysql',
    'driver' => 'pdo_mysql'
], $entityConfiguration);

//Repositories
$threadRepository = $entityManager->getRepository(Thread::class);

//Twig
$templateLoader = new Twig_Loader_Filesystem(__DIR__ . "/src/View");
$twig = new Twig_Environment($templateLoader);

//Application Controllers
$mainController = new MainController($twig, $threadRepository);

//Application
$application = Router::buildApplication()
    ->routeMainController($mainController)
    ->getApplication();