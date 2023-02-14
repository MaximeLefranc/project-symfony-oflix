<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        if (!$movie) {
            throw $this->createNotFoundException('Pas de film ou serie trouvé à l\'id ' . $id);
        }

        return $this->render('main/read.html.twig', [
            'title' => "O'flix - détail",
            'movie' => $movie,
        ]);
    }

    #[Route('/movie/{id<\d+>}/edit', name: 'movie_edit')]
    public function edit(int $id, MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $movieToUpdate = $movieRepository->find($id);

        if (!$movieToUpdate) {
            throw $this->createNotFoundException('Pas de film à l\'id ' . $id);
        }

       $movieToUpdate->setTitle('test');

       $entityManagerInterface->flush();

       return $this->redirectToRoute('movie_read', [ 'id' => $id]);
    }

    #[Route('/movie/add', name: 'movie_add')]
    public function add(MovieRepository $movieRepository): Response
    {
        $newMovie = new Movie();
        $newMovie->setTitle('Mon nouveau film');
        $newMovie->setDuration(500);
        $newMovie->setType('film');

        $movieRepository->save($newMovie, true);

        return $this->redirectToRoute('movie_browse');
    }

    #[Route('/movie/{id<\d+>}/delete', name: 'movie_delete')]
    public function delete(int $id, MovieRepository $movieRepository): Response 
    {
        $movieToDelete = $movieRepository->find($id);

        if(!$movieToDelete) {
            throw $this->createNotFoundException('Pas de film trouvé avec l\id ' . $id);
        }

        $movieRepository->remove($movieToDelete, true);

        return $this->redirectToRoute('movie_browse');
    }
}
