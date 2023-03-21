<?php

namespace App\Controller\Frontoffice;

use App\Repository\GenreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/frontoffice/genre/{id<\d+>}', name: 'app_frontoffice_genre_read')]
    public function read(int $id, GenreRepository $genreRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $genre = $genreRepository->find($id);
        if (!$genre) {
            throw $this->createNotFoundException('Pas de genre trouvé avec cet id: ' . $id);
        }
        $genreName = $genre->getName();

        $movieToShow = $paginatorInterface->paginate(
            $genre->getMovies(), // Requête contenant les données à paginer 
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('frontoffice/genre/index.html.twig', [
            'allGenres' => $genreRepository->findAll(),
            'movieToShow' => $movieToShow,
            'genreName' => $genreName,
            'title' => $genreName . ': films'
        ]);
    }
}
