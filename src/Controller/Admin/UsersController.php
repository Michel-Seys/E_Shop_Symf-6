<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users', name: 'admin_users_')]
/**
 * Summary of UserController
 */
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findBy([], ['firstname' => 'asc']);
        return $this->render('admin/users/index.html.twig', compact('users'));
    }

    #[Route('/modify/{id}', name: 'modify')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyUser(Users $users): Response
    {
        // dd($user);
        $profileForm = $this->createForm(RegistrationFormType::class, $users);

        // dd($profileForm);
        return $this->render('admin/users/details.html.twig', [
            'registrationForm' => $profileForm->createView(),
            'users' => $users
        ]);
    }


}