<?php

namespace App\Controller;

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
    return $this->render('model/index.html.twig', [
      'title' => "Bienvenue sur O'flix !",
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
}
