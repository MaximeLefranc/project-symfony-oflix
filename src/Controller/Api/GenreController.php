<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenreController extends ApiController
{
    #[Route('/api/genres', name: 'app_api_genres', methods: ['GET'])]
    public function browse(GenreRepository $genreRepository): JsonResponse
    {
        return $this->json200($genreRepository->findAll(), ['genre_browse']);
    }

    #[Route('/api/genres/{id<\d+>}', name: 'app_api_genres_read', methods: ['GET'])]
    public function read(Genre $genre = null): JsonResponse
    {
        if (!$genre) {
            return $this->json404('Le genre demandé n\'existe pas.');
        }

        return $this->json200($genre, ['genre_read']);
    }

    #[Route('/api/genres', name: 'app_api_genres_add', methods: ['POST'])]
    public function add(
        Request $request,
        SerializerInterface $serializerInterface,
        GenreRepository $genreRepository,
        ValidatorInterface $validatorInterface
    ): JsonResponse {
        $content = $request->getContent();

        try {
            $newGenre = $serializerInterface->deserialize($content, Genre::class, 'json');
        } catch (Exception $e) {
            return $this->json400('Le JSON est mal formé');
        }

        $errors = $validatorInterface->validate($newGenre);

        if (count($errors) > 0) {
            return $this->json422($errors);
        }

        $genreRepository->save($newGenre, true);

        return $this->json201($newGenre, 'app_api_genres_read', 'id', $newGenre->getId());
    }

    // #[Route('/api/genres/{id<\d+>}', name: 'app_api_genres_edit', methods: ['PUT'])]
    // public function edit(Genre $genre = null): JsonResponse
    // {

    // }
}
