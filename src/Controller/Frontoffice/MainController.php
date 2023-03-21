<?php

namespace App\Controller\Frontoffice;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
  #[Route('/', name: 'movie_browse')]
  public function browse(MovieRepository $movieRepository, GenreRepository $genreRepository, PaginatorInterface $paginator, Request $request): Response
  {
    $allMovies = $movieRepository->findAll();

    $movieToShow = $paginator->paginate(
      $allMovies, // Requête contenant les données à paginer 
      $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
      6 // Nombre de résultats par page
    );

    return $this->render('frontoffice/main/index.html.twig', [
      'title' => "Bienvenue sur O'flix !",
      'allMovies' => $movieToShow,
      'allGenres' => $genreRepository->findAll(),
    ]);
  }

  #[Route('/movie/{slug<[a-zA-Z0-9_-]+>}', name: 'movie_read')]
  public function read($slug, MovieRepository $movieRepository, CastingRepository $castingRepository): Response
  {
    $movie = $movieRepository->findOneBy(['slug' => $slug]);

    if (!$movie) {
      throw $this->createNotFoundException('Pas de film ou serie trouvé avec le titre ' . $slug);
    }

    $allCastingFromMovie = $castingRepository->findBy(['movie' => $movie->getId()], ['creditOrder' => 'ASC']);

    return $this->render('frontoffice/main/read.html.twig', [
      'title' => "O'flix - détail",
      'movie' => $movie,
      'castings' => $allCastingFromMovie,
    ]);
  }

  #[Route('/movie/{id<\d+>}/edit', name: 'movie_edit')]
  public function edit(int $id, MovieRepository $movieRepository, GenreRepository $genreRepository, EntityManagerInterface $entityManagerInterface): Response
  {
    $movieToUpdate = $movieRepository->find($id);

    if (!$movieToUpdate) {
      throw $this->createNotFoundException('Pas de film à l\'id ' . $id);
    }

    $allGenre = $genreRepository->findAll();
    $randNumber = rand(0, count($allGenre) - 1);
    $randGenre = $allGenre[$randNumber];

    $movieToUpdate->addGenre($randGenre);
    $movieToUpdate->setTitle('test');

    $entityManagerInterface->flush();

    return $this->redirectToRoute('movie_read', ['id' => $id]);
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

    if (!$movieToDelete) {
      throw $this->createNotFoundException('Pas de film trouvé avec l\id ' . $id);
    }

    $movieRepository->remove($movieToDelete, true);

    return $this->redirectToRoute('movie_browse');
  }
}
