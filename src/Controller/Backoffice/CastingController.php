<?php

namespace App\Controller\Backoffice;

use App\Entity\Casting;
use App\Form\CastingType;
use App\Repository\CastingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/casting')]
class CastingController extends AbstractController
{
  #[Route('/', name: 'backoffice_casting_index', methods: ['GET'])]
  public function index(CastingRepository $castingRepository): Response
  {
    return $this->render('backoffice/casting/index.html.twig', [
      'castings' => $castingRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'backoffice_casting_new', methods: ['GET', 'POST'])]
  public function new(Request $request, CastingRepository $castingRepository): Response
  {
    $casting = new Casting();
    $form = $this->createForm(CastingType::class, $casting);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $castingRepository->save($casting, true);

        $this->addFlash('success', 'Le casting a bien été ajouté.');
        return $this->redirectToRoute('backoffice_casting_index', [], Response::HTTP_SEE_OTHER);
      }

      $this->addFlash('danger', 'Une erreur est survenue au moment de la création du casting');
    }

    return $this->renderForm('backoffice/casting/new.html.twig', [
      'casting' => $casting,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_casting_show', methods: ['GET'])]
  public function show(Casting $casting): Response
  {
    return $this->render('backoffice/casting/show.html.twig', [
      'casting' => $casting,
    ]);
  }

  #[Route('/{id}/edit', name: 'backoffice_casting_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Casting $casting, CastingRepository $castingRepository): Response
  {
    $form = $this->createForm(CastingType::class, $casting);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $castingRepository->save($casting, true);

        $this->addFlash('success', 'Le casting a bien été modifié.');
        return $this->redirectToRoute('backoffice_casting_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de la modification du casting');
    }

    return $this->renderForm('backoffice/casting/edit.html.twig', [
      'casting' => $casting,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_casting_delete', methods: ['POST'])]
  public function delete(Request $request, Casting $casting, CastingRepository $castingRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $casting->getId(), $request->request->get('_token'))) {
      $castingRepository->remove($casting, true);
      $this->addFlash('success', 'Le casting a bien été supprimé.');
    } else {
      $this->addFlash('danger', 'Une erreur est survenue lors de la suppresion du casting.');
    }

    return $this->redirectToRoute('backoffice_casting_index', [], Response::HTTP_SEE_OTHER);
  }
}
