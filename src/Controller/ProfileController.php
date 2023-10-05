<?php

namespace App\Controller;

use App\Service\passwordService;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/profile', name: 'profile_')]

class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, 
    EntityManagerInterface $entityManager, passwordService $pwdService): Response
    {
        $user = $this->getUser();


        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $pwdService->createPassword($user, $userPasswordHasher, $form);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'La modification de l\'utilisateur est enregistrée !');

            return $this->redirectToRoute('app_main');
        }

        $this->addFlash('danger', 'La modification à échouée !');

        return $this->render('profile/edit.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }
}
