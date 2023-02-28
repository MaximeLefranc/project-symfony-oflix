<?php

namespace App\Controller\Backoffice;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Services\MySlugger;
use App\Services\OmdbApi;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/backoffice/movie')]
class MovieController extends AbstractController
{
  #[Route('/', name: 'app_backoffice_movie_index', methods: ['GET'])]
  public function index(MovieRepository $movieRepository): Response
  {
    // $this->denyAccessUnlessGranted('ROLE_LIST');

    return $this->render('backoffice/movie/index.html.twig', [
      'movies' => $movieRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_backoffice_movie_new', methods: ['GET', 'POST'])]
  #[IsGranted('ROLE_MANAGER')]
  public function new(Request $request, MovieRepository $movieRepository, OmdbApi $omdbApi, MySlugger $slugger): Response
  {
    // $this->denyAccessUnlessGranted('ROLE_ADD');

    $movie = new Movie();
    $form = $this->createForm(MovieType::class, $movie);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $movie->setSlug($slugger->slugify($movie->getTitle()));
        // Search poster with the name of movie
        $movie->setPoster($omdbApi->fetchPoster($movie->getTitle()));
        $movieRepository->save($movie, true);

        $this->addFlash('success', 'Le film/serie a bien été ajouté.');
        return $this->redirectToRoute('app_backoffice_movie_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout du film ou de la serie');
    }

    return $this->renderForm('backoffice/movie/new.html.twig', [
      'movie' => $movie,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_backoffice_movie_show', methods: ['GET'])]
  public function show(Movie $movie): Response
  {
    return $this->render('backoffice/movie/show.html.twig', [
      'movie' => $movie,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_backoffice_movie_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Movie $movie, MovieRepository $movieRepository, MySlugger $slugger): Response
  {
    $form = $this->createForm(MovieType::class, $movie);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $movie->setSlug($slugger->slugify($movie->getTitle()));
        $movie->setUpdatedAt(new DateTime());
        $movieRepository->save($movie, true);

        $this->addFlash('success', 'Le film a bien été modifié.');

        return $this->redirectToRoute('app_backoffice_movie_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Le film n\'a pas pu être modifié !');
    }

    return $this->renderForm('backoffice/movie/edit.html.twig', [
      'movie' => $movie,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_backoffice_movie_delete', methods: ['POST'])]
  #[IsGranted('ROLE_ADMIN')]
  public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
      $movieRepository->remove($movie, true);
      $this->addFlash('success', 'Le film ou la série a bien été supprimé.');
    } else {
      $this->addFlash('danger', 'Une erreur est survenue lors de la suppresion duy film ou de la serie.');
    }

    return $this->redirectToRoute('app_backoffice_movie_index', [], Response::HTTP_SEE_OTHER);
  }
}
