<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Api\ApiController;

class MovieController extends ApiController
{
    #[Route('/api/movies', name: 'app_api_movie')]
    public function browse(MovieRepository $movieRepository): JsonResponse
    {

        return $this->json200(
            $movieRepository->findAll(),
            ['movie_browse']
        );
    }
}
