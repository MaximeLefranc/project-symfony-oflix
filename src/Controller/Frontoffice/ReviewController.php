<?php

namespace App\Controller\Frontoffice;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
  #[Route('/movie/{id<\d+>}/review', name: 'review_add', methods: ['GET', 'POST'])]
  public function add(int $id, MovieRepository $movieRepository, ReviewRepository $reviewRepository, Request $request): Response
  {
    $movie = $movieRepository->find($id);
    if (!$movie) {
      throw $this->createNotFoundException('Film introuvable avec l\'id ' . $id);
    }

    $review = new Review();
    $form = $this->createForm(ReviewType::class, $review);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $review->setMovie($movie);
      $reviewRepository->save($review, true);

      return $this->redirectToRoute('movie_read', ['slug' => $movie->getSlug()]);
    }

    return $this->render('frontoffice/review/add-rewiew.html.twig', [
      'movie' => $movie,
      'form' => $form->createView(),
    ]);
  }
}
