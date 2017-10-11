<?php

use BiffBangPow\MessageBoard\Model\Thread;
use BiffBangPow\MessageBoard\Model\Comment;
use BiffBangPow\MessageBoard\Model\User;
use BiffBangPow\MessageBoard\Router;
use BiffBangPow\MessageBoard\Controller\MainController;
use BiffBangPow\MessageBoard\Controller\ThreadController;
use BiffBangPow\MessageBoard\Controller\CommentController;
use BiffBangPow\MessageBoard\Controller\UserController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Silex\Application;
use \BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use \BiffBangPow\MessageBoard\FormHandler\CommentFormHandler;
use \BiffBangPow\MessageBoard\FormHandler\UserFormHandler;
use \BiffBangPow\MessageBoard\Services\SessionService;
use \BiffBangPow\MessageBoard\Model\Report;

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
$userRepository = $entityManager->getRepository(User::class);
$reportRepository = $entityManager->getRepository(Report::class);

//Twig
session_start();
$templateLoader = new Twig_Loader_Filesystem(__DIR__ . "/src/View");
$twig = new Twig_Environment($templateLoader);
$twig->addGlobal('session', $_SESSION);

//Services
$sessionService = new SessionService();

//FormHandlers
$threadFormHandler = new ThreadFormHandler($entityManager, $sessionService, $userRepository);
$commentFormHandler = new CommentFormHandler($entityManager, $threadRepository, $sessionService, $userRepository, $commentRepository);
$userFormHandler = new UserFormHandler($entityManager, $sessionService);

//Application Controllers
$mainController = new MainController($twig, $threadRepository, $commentRepository, $userRepository, $sessionService);
$threadController = new ThreadController($twig, $threadRepository, $threadFormHandler, $sessionService);
$commentController = new CommentController($twig, $commentRepository,$reportRepository ,$commentFormHandler, $sessionService);
$userController = new UserController($twig, $userRepository, $userFormHandler, $sessionService);

//Application
$application = new Application(['debug' => true]);
$router = new Router($application);
$router
    ->routeMainController($mainController)
    ->routeThreadController($threadController)
    ->routeCommentController($commentController)
    ->routeUserController($userController)
;
