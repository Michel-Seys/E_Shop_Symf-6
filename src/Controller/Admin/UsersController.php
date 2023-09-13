<?php

namespace App\Controller\Admin;
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
    public function index(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

}