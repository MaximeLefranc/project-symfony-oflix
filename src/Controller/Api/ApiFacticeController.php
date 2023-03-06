<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiFacticeController extends AbstractController
{
    #[Route('/api/factice/movies', name: 'app_api_movies_factice')]
    public function index(): JsonResponse
    {
        $movie = new Movie();
        $movie->setTitle('Titre test');
        $movie->setSummary('test sommaire');
        $movie->setSlug('titre-test');
        // ....
        return $this->json($movie, Response::HTTP_OK);
    }
}
