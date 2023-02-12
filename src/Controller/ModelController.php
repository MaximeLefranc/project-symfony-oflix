<?php

namespace App\Controller;

use App\Models\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController 
{
  /**
   * Home page
   * @Route ("/model", name="home")
   * @return Response
   */
  public function home(): Response
  {
    $movieModel = new Movies();
    $allMovies = $movieModel->getAll();

    return $this->render('model/index.html.twig', [
      'title' => "Bienvenue sur O'flix !",
      'allMovies' => $allMovies,
    ]);
  }

  /**
   * Search page
   * @Route("/model/search", name="search")
   * @return Response
   */
  public function search(): Response
  {
    return $this->render('model/list.html.twig', [
      'title' => 'Résultat de recherche',
    ]);
  }

  /**
   * Favories page
   * @Route("/model/favorites", name="favories")
   * @return Response
   */
  public function favories(): Response 
  {
    return $this->render('model/favorite.html.twig', [
      'title' => 'Vos favoris',
    ]);
  }

  /**
   * Page of movies detail 
   * @Route("/model/show/{id}", name="movie-detail-by-id", requirements={"id"="\d+"})
   * @param int $id
   * @return Response
   */
  public function show($id): Response
  {
    $movieModel = new Movies();
    $movie = $movieModel->getById($id);
    return $this->render('model/show.html.twig', [
      'title' => "O'flix - Détails",
      'movie' => $movie,
    ]);
  }
}
