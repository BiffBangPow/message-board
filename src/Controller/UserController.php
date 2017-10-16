<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\FormHandler\UserFormHandler;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BiffBangPow\MessageBoard\Services\SessionService;

class UserController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityRepository
     */
    private $userRepository;
    /**
     * @var UserFormHandler
     */
    private $userFormHandler;
    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * Controller constructor.
     * @param \Twig_Environment $twig
     * @param EntityRepository $userRepository
     * @param UserFormHandler $userFormHandler
     * @internal param EntityRepository $threadRepository
     */
    public function __construct(\Twig_Environment $twig, EntityRepository $userRepository, UserFormHandler $userFormHandler,SessionService $sessionService)
    {
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->userFormHandler = $userFormHandler;
        $this->sessionService = $sessionService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getLoginAction(Request $request)
    {
        return new Response($this->twig->render('login.html.twig', []));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function loginAction(Request $request)
    {
        $success = $this->userFormHandler->handleLogin($request);

        if ($success) {
            $this->sessionService->setIsLoggedIn(true);

            return new RedirectResponse('/');
        } else {
            throw new \Exception("Login Unsuccessful");
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getRegisterAction(Request $request)
    {
        return new Response($this->twig->render('register.html.twig', []));
    }

    /**
 * @param Request $request
 * @return RedirectResponse
 */
    public function registerAction(Request $request)
    {
        $this->userFormHandler->handleRegistration($request);

        $this->sessionService->setIsLoggedIn(true);

        return new RedirectResponse('/');
    }
}