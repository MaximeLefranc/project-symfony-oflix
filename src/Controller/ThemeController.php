<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ThemeController extends AbstractController
{
  /**
   * Theme toggle 
   *
   * @Route("/theme", name="toggle-theme")
   * @return void
   */
  public function toggle(SessionInterface $session): Response
  {
    // catch the session
    // thanks injection
    //? dump($session);

    // Do the toggle
    // Catch the value of key 'theme'
    $theme = $session->get('theme', 'netflix');

    if($theme === 'netflix') {
      $session->set('theme', 'allocine');
    } else {
      // If theme === null so value per default
      $session->set('theme', 'netflix');
    }

    // Redirect user 
    return $this->redirectToRoute('movie_browse');
  }
}
