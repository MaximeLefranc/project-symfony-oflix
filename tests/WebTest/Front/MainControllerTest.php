<?php

namespace App\Tests\WebTest\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

class MainControllerTest extends WebTestCase
{
  public function testRedirectRoad(): void
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/backoffice/casting');

    // Status code === 200
    // $this->assertResponseIsSuccessful();
    $this->assertResponseRedirects();

    /** @var UserRepository */
    $userRespository = static::getContainer()->get(UserRepository::class);
    $admin = $userRespository->findOneBy(['email' => 'maxilefranc@gmail.com']);

    $client->loginUser($admin);


    $this->assertResponseStatusCodeSame(301);
  }

  protected function onNotSuccessfulTest(Throwable $t): void
  {
    if (strpos($t->getTraceAsString(), 'assertResponse') > 0) {
      $arrayMessage = explode("\n", $t->getMessage());
      $message = $arrayMessage[0] . "\n" . $arrayMessage[1];
      $this->fail($message);
    }

    throw $t;
  }
}
