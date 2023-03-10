<?php

namespace App\Tests;

use App\Services\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbapiTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        /** @var OmdbApi */
        $myOmdbApi = static::getContainer()->get(OmdbApi::class);

        $posterTotoro = $myOmdbApi->fetchPoster('Totoro');
        $urlPoster = "https://m.media-amazon.com/images/M/MV5BYzJjMTYyMjQtZDI0My00ZjE2LTkyNGYtOTllNGQxNDMyZjE0XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg";

        // He founds a poster with the movie title
        $this->assertEquals($posterTotoro, $urlPoster);

        $titleNotFound = $myOmdbApi->fetchPoster('Not exist movie');
        $defaultUrlPoster = 'https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg';

        $this->assertEquals($defaultUrlPoster, $titleNotFound);
    }
}
