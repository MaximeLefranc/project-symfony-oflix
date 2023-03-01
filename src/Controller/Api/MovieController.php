<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Api\ApiController;
use App\Entity\Movie;

class MovieController extends ApiController
{
    #[Route('/api/movies', name: 'app_api_movies_browse')]
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        return $this->json200($movieRepository->findAll(), ['movie_browse']);
    }

    #[Route('api/movies/{id<\d+>}', name: 'app_api_movies_read')]
    public function read(Movie $movie = null)
    {
        if (!$movie) {
            return $this->json404('Le film n\'existe pas !');
        }

        return $this->json200(
            $movie,
            ['movie_browse']
        );
    }
}
