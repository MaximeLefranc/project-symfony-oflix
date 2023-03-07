<?php

namespace App\Tests\WebTest\Front;

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

		$crawler = $client->followRedirect();

		$this->assertSelectorTextContains('h1', 'Casting index');
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
