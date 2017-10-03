<?php

use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Model\Comment;
use BiffBangPow\MessageBoard\Router;
use BiffBangPow\MessageBoard\Controller\MainController;
use BiffBangPow\MessageBoard\Controller\ThreadController;
use BiffBangPow\MessageBoard\Controller\CommentController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Silex\Application;
use \BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use \BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use \BiffBangPow\MessageBoard\FormHandler\CommentFormHandler;

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
$commentRepository = $entityManager->getRepository(Comment::class);

//Twig
$templateLoader = new Twig_Loader_Filesystem(__DIR__ . "/src/View");
$twig = new Twig_Environment($templateLoader);


//FormHandlers
$threadFormHandler = new ThreadFormHandler($entityManager);
$commentFormHandler = new CommentFormHandler($entityManager, $threadRepository);

//Application Controllers
$mainController = new MainController($twig, $threadRepository, $commentRepository,$entityManager);
$threadController = new ThreadController($twig, $threadRepository, $threadFormHandler);
$commentController = new CommentController($twig, $commentRepository, $commentFormHandler);

//Application
$application = new Application(['debug' => true]);
$router = new Router($application);
$router->routeMainController($mainController);
$router->routeCommentController($commentController);

$router ->routeThreadController($threadController);
