<?php

namespace App\Controller\Backoffice;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/person')]
class PersonController extends AbstractController
{
  #[Route('/', name: 'backoffice_person_index', methods: ['GET'])]
  public function index(PersonRepository $personRepository): Response
  {
    return $this->render('backoffice/person/index.html.twig', [
      'people' => $personRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'backoffice_person_new', methods: ['GET', 'POST'])]
  public function new(Request $request, PersonRepository $personRepository): Response
  {
    $person = new Person();
    $form = $this->createForm(PersonType::class, $person);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $personRepository->save($person, true);

        $this->addFlash('success', 'L\'acteur a bien été ajouté.');
        return $this->redirectToRoute('backoffice_person_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout de l\'acteur.');
    }

    return $this->renderForm('backoffice/person/new.html.twig', [
      'person' => $person,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_person_show', methods: ['GET'])]
  public function show(Person $person): Response
  {
    return $this->render('backoffice/person/show.html.twig', [
      'person' => $person,
    ]);
  }

  #[Route('/{id}/edit', name: 'backoffice_person_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Person $person, PersonRepository $personRepository): Response
  {
    $form = $this->createForm(PersonType::class, $person);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $personRepository->save($person, true);

        $this->addFlash('success', 'L\'acteur a été modifié avec succès.');
        return $this->redirectToRoute('app_backoffice_person_index', [], Response::HTTP_SEE_OTHER);
      }
      $this->addFlash('danger', 'Une erreur est survenue lors de la modification de l\'acteur.');
    }

    return $this->renderForm('backoffice/person/edit.html.twig', [
      'person' => $person,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'backoffice_person_delete', methods: ['POST'])]
  public function delete(Request $request, Person $person, PersonRepository $personRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $person->getId(), $request->request->get('_token'))) {
      $personRepository->remove($person, true);
      $this->addFlash('success', 'L\'acteur a bien été supprimé.');
    } else {
      $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de l\'acteur.');
    }

    return $this->redirectToRoute('backoffice_person_index', [], Response::HTTP_SEE_OTHER);
  }
}
