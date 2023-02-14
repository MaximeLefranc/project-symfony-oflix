<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'movie_browse')]
    public function browse(MovieRepository $movieRepository): Response
    { 
        $allMovies = $movieRepository->findAll();

        return $this->render('main/index.html.twig', [
            'title' => "Bienvenue sur O'flix !",
            'allMovies' => $allMovies,
        ]);
    }

    #[Route('/movie/{id<\d+>}', name: 'movie_read')]
    public function read($id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        if(!$movie) {
          throw $this->createNotFoundException('Pas de film ou serie trouvé à l\'id ' . $id);
        }

        return $this->render('main/read.html.twig', [
            'title' => "O'flix - détail",
            'movie' => $movie,
        ]);
    }
}
