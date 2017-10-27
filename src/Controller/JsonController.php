<?php

namespace BiffBangPow\MessageBoard\Controller;

use BiffBangPow\MessageBoard\Model\Thread;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

class JsonController
{
    /**
     * @var EntityRepository
     */
    private $threadRepository;

    /**
     * @var Serializer
     */
    private $serializerService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityManager
     */
    private $userRepository;


    /**
     * @param EntityRepository $threadRepository
     * @param EntityRepository $userRepository
     * @param Serializer $serializerService
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityRepository $threadRepository,
        EntityRepository $userRepository,
        Serializer $serializerService,
        EntityManager $entityManager
    )
    {
        $this->threadRepository = $threadRepository;
        $this->userRepository = $userRepository;
        $this->serializerService = $serializerService;
        $this->entityManager = $entityManager;
    }


    /**
     * @param Request $request
     * @param int $id
     * Todo: This could use the same route as /thread/{id}. We could optimise the routes at a later date
     */
    public function apiViewAction(int $id)
    {
        /** @var Thread $thread */
        $thread = $this->threadRepository->find($id);

        if ($thread === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new Response($this->serializerService->serialize($thread, 'json'), Response::HTTP_OK, [
            "Content-Type" => 'application/json'
        ]);
    }

    /**
     * @param Request $request
     * Todo: This could use the same route as /thread/{id}. We could optimise the routes at a later date
     */
    public function apiViewActionList(Request $request)
    {
        // Todo: Change this into a re-usable component
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 5));
        $offset = ($page - 1) * $limit;

        $allThreads = $this->threadRepository->createQueryBuilder('t')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();

        return new Response($this->serializerService->serialize($allThreads, 'json'), Response::HTTP_OK, [
            "Content-Type" => 'application/json'
        ]);
    }


    /**
     * @param Request $request
     * Todo: Look into validator, Silex: https://silex.symfony.com/doc/2.0/providers/validator.html#validating-values
     */
    public function apiCreateAction(Request $request)
    {
        $thread = new Thread();
        $threadTitle = $request->get('title');
        $thread->setTitle($threadTitle);
        $threadContent = $request->get('content');
        $thread->setContent($threadContent);

        $threadUserId = intval($request->get('user_id'));
        $user = $this->userRepository->find($threadUserId);

        $error = [
            'Error' => 'User not found!'
        ];

        $thread->setUser($user);
        $this->entityManager->persist($thread);
        $this->entityManager->flush();

        if ($user === null) {
            return new JsonResponse($error, Response::HTTP_ACCEPTED);
        }

        return new Response($this->serializerService->serialize($thread, 'json'), Response::HTTP_CREATED, [
            "Content-Type" => 'application/json'
        ]);
    }

    /**
     * @param Request $request
     */
    public function apiUpdateAction(Request $request)
    {
        return null;
    }

    /**
     * @param Request $request
     */
    public function apiDeleteAction(int $id)
    {
        /** @var Thread $thread */
        $thread = $this->threadRepository->find($id);

        if ($thread === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($thread);
        $this->entityManager->flush();

        $removedMsg = [
            'Success' => 'Entry deleted!'
        ];

        return new JsonResponse($removedMsg, Response::HTTP_OK, [
            "Content-Type" => 'application/json'
        ]);
    }
}