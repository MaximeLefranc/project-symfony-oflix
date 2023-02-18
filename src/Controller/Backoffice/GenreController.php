<?php

namespace App\Controller\Backoffice;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/genre')]
class GenreController extends AbstractController
{
  #[Route('/', name: 'backoffice_genre_index', methods: ['GET'])]
  public function index(GenreRepository $genreRepository): Response
  {
    return $this->render('backoffice/genre/index.html.twig', [
      'genres' => $genreRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'backoffice_genre_new', methods: ['GET', 'POST'])]
  public function new(Request $request, GenreRepository $genreRepository): Response
  {
    $genre = new Genre();
    $form = $this->createForm(GenreType::class, $genre);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $genreRepository->save($genre, true);

        $this->addFlash('success', 'Le genre a bien été ajouté.');
        return $this->redirectToRoute('backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout du genre.');
    }

    return $this->renderForm('backoffice/genre/new.html.twig', [
      'genre' => $genre,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_genre_show', methods: ['GET'])]
  public function show(Genre $genre): Response
  {
    return $this->render('backoffice/genre/show.html.twig', [
      'genre' => $genre,
    ]);
  }

  #[Route('/{id}/edit', name: 'backoffice_genre_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Genre $genre, GenreRepository $genreRepository): Response
  {
    $form = $this->createForm(GenreType::class, $genre);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $genreRepository->save($genre, true);

        $this->addFlash('success', 'Le genre a bien été modifié.');
        return $this->redirectToRoute('backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de la modification du genre.');
    }

    return $this->renderForm('backoffice/genre/edit.html.twig', [
      'genre' => $genre,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_genre_delete', methods: ['POST'])]
  public function delete(Request $request, Genre $genre, GenreRepository $genreRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->request->get('_token'))) {
      $genreRepository->remove($genre, true);
      $this->addFlash('success', 'Le genre a bien été supprimé.');
    } else {
      $this->addFlash('danger', 'Une erreur est survenue lors de la suppression du genre.');
    }

    return $this->redirectToRoute('backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
  }
}
