<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MovieTest extends WebTestCase
{
    public function testStandardUserCanNotAccessPages(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $standardUser = $userRepository->findOneByEmail('user@user.com');

        // simulate $standardUser being logged in
        $client->loginUser($standardUser);

        $securedRoutes = [
            '/back/movie',
            '/back/user',
            '/back/user/new',
        ];

        foreach($securedRoutes as $currentRoutes)
        {
            // test e.g. the profile page
            $client->request('GET', $currentRoutes);
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

    public function testManagerPageAccess(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $managerUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $managerUser being logged in
        $client->loginUser($managerUser);

        $forbiddenRoutes = [
            '/back/user/new',
        ];

        foreach($forbiddenRoutes as $currentRoutes)
        {
            // test e.g. the profile page
            $client->request('GET', $currentRoutes);
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }

        $authorizedRoutes = [
            '/back/movie',
            '/back/user',
        ];

        foreach($authorizedRoutes as $currentRoutes)
        {
            // test e.g. the profile page
            $client->request('GET', $currentRoutes);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }
    }


    /**
     * @dataProvider routesForUser
     * 
     * @return void
     */
    public function testStandardUserPageAccess($urlToTest, $expectedCode, $httpMethod): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $standardUser = $userRepository->findOneByEmail('user@user.com');

        // simulate $standardUser being logged in
        $client->loginUser($standardUser);

        // test e.g. the profile page
        $client->request($httpMethod, $urlToTest);
        $this->assertResponseStatusCodeSame($expectedCode);
    }

    public function routesForUser()
    {
        return [
            ['/back/movie/new', Response::HTTP_FORBIDDEN, Request::METHOD_POST],
            ['/back/user/new' , Response::HTTP_FORBIDDEN, Request::METHOD_POST],

            ['/back/movie', Response::HTTP_FORBIDDEN, Request::METHOD_GET],
            ['/back/user', Response::HTTP_FORBIDDEN, Request::METHOD_GET],
            ['/back/user/new', Response::HTTP_FORBIDDEN, Request::METHOD_GET],
        ];
    }
}
