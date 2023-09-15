<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'admin_products_')]
/**
 * Summary of UserController
 */
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/add', name: 'add')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/edit/{id}', name: 'edit')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Products $product): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        return $this->render('admin/products/index.html.twig');
    }
   
    #[Route('/delete/{id}', name: 'delete')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Products $product): Response
    {
        return $this->render('admin/products/index.html.twig');
    }

    

}