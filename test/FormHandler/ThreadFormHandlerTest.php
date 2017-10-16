<?php
/**
 * Created by PhpStorm.
 * User: placement
 * Date: 05/10/2017
 * Time: 15:13
 */

use BiffBangPow\MessageBoard\FormHandler\ThreadFormHandler;
use \Mockery as m;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use \Symfony\Component\HttpFoundation\Request;
use BiffBangPow\MessageBoard\Services\SessionService;
use Doctrine\ORM\EntityRepository;

class ThreadFormHandlerTest extends TestCase
{
    public function testThreadFormHandler_calls_persist()
    {
        $mockEntityManager = m::mock(EntityManager::class);
        $mockSessionService = m::mock(SessionService::class);
        $mockUserRepository = m::mock(EntityRepository::class);
        $mockRequest = m::mock(Request::class);

        $mockSessionService->shouldReceive('getUserId')->andReturn(1);
        $mockUserRepository->shouldReceive('find');

        $mockRequest->shouldReceive('get')->with('title')->andReturn('lorem ipsum');
        $mockRequest->shouldReceive('get')->with('content')->andReturn('lorem ipsum');

        $mockEntityManager->shouldReceive('persist');

        $mockEntityManager->shouldReceive('flush');

        $threadFormHandler = new ThreadFormHandler($mockEntityManager, $mockSessionService, $mockUserRepository);

        $threadFormHandler->handle($mockRequest);

        $mockEntityManager->shouldHaveReceived('persist');
    }
}
