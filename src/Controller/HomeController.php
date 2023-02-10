<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
  /**
   * Home Page
   *
   * @Route("/bonjour", name="MaZolieRoute")
   * Par défault le nom d'une route est : Namespace_Controller_Method -> Pour avoir un name custom nous ajoutons un paramètre "name" dans le Doc-Block
   * ! Symfony 6 -> Nouvelle façon de faire les routes   #[Route('/bonjour', name: 'MaZolieRoute')]
   * @return Response
   */
  public function home(): Response
  {
    $response = new Response('Salut les incas');

    return $response;
  }
}
