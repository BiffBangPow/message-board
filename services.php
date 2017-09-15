<?php

use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Router;
use BiffBangPow\MessageBoard\Controller\MainController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Silex\Application;

require_once __DIR__ . "/vendor/autoload.php";

//Doctrine ORM
$entityConfiguration = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src/Model"], false);
$entityManager = EntityManager::create([
    'dbname' => $_ENV['MYSQL_DATABASE'],
    'user' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_PASSWORD'],
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
$application = new Application(['debug' => true]);
$router = new Router($application);
$router->routeMainController($mainController);