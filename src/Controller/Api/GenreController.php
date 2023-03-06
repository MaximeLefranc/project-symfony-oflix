<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->json(
				'Petit maladrin :D',
				Response::HTTP_FORBIDDEN
			);
		}
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

		return $this->json201($newGenre, 'app_api_genres_read', 'id', $newGenre->getId(), ['genre_read']);
	}

	#[Route('/api/genres/{id<\d+>}', name: 'app_api_genres_edit', methods: ['PUT', 'PATCH'])]
	public function edit(
		?Genre $genre,
		Request $request,
		SerializerInterface $serializerInterface,
		EntityManagerInterface $entityManagerInterface
	): JsonResponse {

		if (!$this->isGranted('ROLE_ADMIN')) {
			dd('je suis ici');
			return $this->json(
				'Petit maladrin :D',
				Response::HTTP_FORBIDDEN
			);
		}
		if (!$genre) {
			return $this->json404('Pas de genre existant avec cet ID.');
		}

		$jsonContent = $request->getContent();

		$serializerInterface->deserialize($jsonContent, Genre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]);

		$entityManagerInterface->flush();

		return $this->json206($genre, 'app_api_genres_read', 'id', $genre->getId(), ['genre_read']);
	}
}
