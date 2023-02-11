<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
  /**
   * Home Page
   *
   * @Route("/demo/bonjour", name="MaZolieRoute")
   * Par défault le nom d'une route est : Namespace_Controller_Method -> Pour avoir un name custom nous ajoutons un paramètre "name" dans le Doc-Block
   * ! Symfony 6 -> Nouvelle façon de faire les routes   #[Route('/demo/bonjour', name: 'MaZolieRoute')]
   * @return Response
   */
  public function home(): Response
  {
    $response = new Response('Salut les incas');
  
    return $response;
  }

  /**
   * Demo avec twig
   * @Route("/demo/twig")
   * @return Response
   */
  public function demoTwig(): Response
  {
    return $this->render('demo/index.html.twig',
      [
        'titre_page' => 'Le super titre', 
        'autre_variable' => 'Autre valeur'
      ]
    );
  }
}
