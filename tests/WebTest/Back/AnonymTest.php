<?php

namespace App\Tests\WebTest\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnonymTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/backoffice/movie/');

        // Redirect to login road because we aren't logged
        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);

        /** @var UserRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findOneBy(["email" => "maxilefranc@gmail.com"]);

        $client->loginUser($admin);

        $crawler = $client->request('GET', '/backoffice/movie/');

        $this->assertResponseIsSuccessful();
    }
}
