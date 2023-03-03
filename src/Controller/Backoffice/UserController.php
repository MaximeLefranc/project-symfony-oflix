<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'backoffice_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'backoffice_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->createAccessDeniedException('Pas les roles nécéssaires pour accéder à cette page.');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);
                $userRepository->save($user, true);

                $this->addFlash('success', 'L\'utilisateur a bien été créé.');
                return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement de l\'utilisateur');
        }

        return $this->renderForm('backoffice/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backoffice_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('backoffice/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'backoffice_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $newPassword = $form->get('password')->getData();
                if ($newPassword) {
                    $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($hashedPassword);
                }
                $userRepository->save($user, true);

                $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');
                return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'Une erreur est survenue lors de la mise à jour de cet utilisateur.');
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backoffice_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
