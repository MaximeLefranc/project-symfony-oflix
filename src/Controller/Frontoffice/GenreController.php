<?php

namespace App\Controller\Frontoffice;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/frontoffice/genre/{name<\w+|\W+>}', name: 'app_frontoffice_genre_read')]
    public function read(string $name, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findOneBy(['name' => $name]);
        if (!$genre) {
            throw $this->createNotFoundException('Pas de genre trouvé avec ce nom: ' . $name);
        }
        $genreName = $genre->getName();

        return $this->render('frontoffice/genre/index.html.twig', [
            'allGenres' => $genreRepository->findAll(),
            'genre' => $genre,
            'genreName' => $genre->getName(),
            'title' => $genre->getName() . ': films'
        ]);
    }
}
