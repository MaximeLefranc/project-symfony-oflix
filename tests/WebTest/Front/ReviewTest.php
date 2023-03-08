<?php

namespace App\Tests\WebTest\Front;

use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        /** @var MovieRepository */
        $movieRespository = static::getContainer()->get(MovieRepository::class);
        /** @var UserRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        $RandomMovie = $movieRespository->findRandomMovie();

        $crawler = $client->request('GET', '/movie/' . $RandomMovie['id'] . '/review');

        $this->assertResponseRedirects('/login');

        $admin = $userRepository->findOneBy(['email' => 'maxilefranc@gmail.com']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/movie/' . $RandomMovie['id'] . '/review');

        $buttonCrawlerNode = $crawler->selectButton('Envoyer');

        $form = $buttonCrawlerNode->form();

        $form['review[username]'] = 'Les Incas';
        $form['review[email]'] = 'nain@porte.koi';
        $form['review[content]'] = 'encore mieux que le premier !';
        $form['review[rating]'] = '5';
        $form['review[reactions]'] = ['Cry'];
        $form['review[watchedAt]'] = '2023-03-02';

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);
    }
}
