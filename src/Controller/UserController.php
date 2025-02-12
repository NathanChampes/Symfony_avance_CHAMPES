<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Alors la on défini la route de base pour ce controller
#[Route('/admin/users')]
// Je m'assure également que seul les admins ont accès à ce controller
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($plainPassword = $form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }
            
            // S'assurer que ROLE_USER est toujours présent
            $roles = $form->get('roles')->getData();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
            }
            $user->setRoles(array_unique($roles));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'user.created_successfully');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $currentRoles = $user->getRoles();
        
        $form = $this->createForm(UserType::class, $user, [
            'is_new' => false,
            'current_roles' => $currentRoles
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
            }

            // Si l'utilisateur avait déjà ROLE_ADMIN, conservez-le
            if (in_array('ROLE_ADMIN', $currentRoles) && !in_array('ROLE_ADMIN', $roles)) {
                $this->addFlash('error', 'user.cannot_remove_admin');
                return $this->redirectToRoute('app_user_edit', ['id' => $user->getId()]);
            }

            $user->setRoles(array_unique($roles));
            $entityManager->flush();
            
            $this->addFlash('success', 'user.updated_successfully');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est un admin
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('error', 'user.cannot_delete_admin');
            return $this->redirectToRoute('app_user_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'user.deleted_successfully');
        }

        return $this->redirectToRoute('app_user_index');
    }
}
