<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi
{
    private $httpClient;
    private $omdbApiKey;

    public function __construct(HttpClientInterface $client, $apiKey)
    {
        $this->httpClient = $client;
        $this->omdbApiKey = $apiKey;
    }

    /**
     * Fetch all infos from movie or serie title
     *
     * @param string $title
     * @return array
     */
    public function fetch(string $title)
    {
        // http://www.omdbapi.com/?apikey=[yourkey]&
        $response = $this->httpClient->request(
            'GET',
            'http://www.omdbapi.com/?t=' . $title . '&apikey=' . $this->omdbApiKey
        );

        return $response->toArray();
    }

    /**
     * Fetch the poster of movie or serie from title
     *
     * @param string $title
     * @return string
     */
    public function fetchPoster(string $title)
    {
        $allInfos = $this->fetch($title);

        if (array_key_exists('Poster', $allInfos)) {
            return $allInfos['Poster'];
        }

        return 'https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg';
    }
}
