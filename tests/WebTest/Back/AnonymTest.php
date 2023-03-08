<?php

namespace App\Tests\WebTest\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnonymTest extends WebTestCase
{
    /**
     * Anonym test
     *
     * @param string $url
     * @return void
     * @dataProvider getUrls
     */
    public function testSomething($url, $urlRedirect): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        // Redirect to login road because we aren't logged
        $this->assertResponseRedirects($urlRedirect, Response::HTTP_FOUND);
    }

    /**
     * Function used by dataProvider
     *
     * @return void
     */
    public function getUrls()
    {
        // yield means return + i waiting another call 
        //     $url                 $urlRedirect
        yield ['/backoffice/movie/', '/login'];
        yield ['/backoffice/genre/', '/login'];
        yield ['/backoffice/season/', '/login'];
        yield ['/backoffice/person/', '/login'];
        yield ['/backoffice/casting/', '/login'];
    }

    public function testWithUser(): void
    {
        $client = static::createClient();
        /** @var UserRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findOneBy(["email" => "maxilefranc@gmail.com"]);

        $client->loginUser($admin);

        $crawler = $client->request('GET', '/backoffice/movie/');

        $this->assertResponseIsSuccessful();
    }
}
