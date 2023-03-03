<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Api\ApiController;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends ApiController
{
    #[Route('/api/movies', name: 'app_api_movies_browse', methods: ['GET'])]
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        return $this->json200($movieRepository->findAll(), ['movie_browse']);
    }

    #[Route('api/movies/{id<\d+>}', name: 'app_api_movies_read', methods: ['GET'])]
    public function read(Movie $movie = null): JsonResponse
    {
        if (!$movie) {
            return $this->json404('Le film n\'existe pas !');
        }

        return $this->json200(
            $movie,
            ['movie_read']
        );
    }

    #[Route('/api/movies', name: 'app_api_movies_add', methods: ['POST'])]
    public function add(
        Request $request,
        ValidatorInterface $validatorInterface,
        MovieRepository $movieRepository,
        SerializerInterface $serializerInterface
    ): JsonResponse {
        $content = $request->getContent();

        try {
            $newMovie = $serializerInterface->deserialize($content, Movie::class, 'json');
        } catch (Exception $e) {
            return $this->json400('Le JSON est mal formé.');
        }

        $errors = $validatorInterface->validate($newMovie);

        if (count($errors) > 0) {
            return $this->json422($errors);
        }

        $movieRepository->save($newMovie, true);

        return $this->json201($newMovie, 'app_api_movies_read', 'id', $newMovie->getId(), ['movie_read']);
    }

    #[Route('api/movies/{id<\d+>}', name: 'app_api_movies_edit', methods: ['PUT', 'PATCH'])]
    public function edit(
        ?Movie $movie,
        Request $request,
        SerializerInterface $serializerInterface,
        EntityManagerInterface $entityManagerInterface
    ): JsonResponse {
        if (!$movie) {
            return $this->json400('Aucun film trouvé avec cet ID.');
        }

        $jsonContent = $request->getContent();

        $serializerInterface->deserialize($jsonContent, Movie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $movie]);

        $entityManagerInterface->flush();

        return $this->json206($movie, 'app_api_movies_read', 'id', $movie->getId(), ['movie_read']);
    }
}
