<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Creation nouveau produit
        $product = new Products();

        //Creation formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        //Traitement de la requete
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $prix= $product->getPrice() * 100;
            $product->setPrice($prix);
            
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté !');

            $this->redirectToRoute('admin_products_index');

        }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    /**
     * Summary of index
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        $prix= $product->getPrice() / 100;
        $product->setPrice($prix);
        //Creation formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        //Traitement de la requete
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            
            $prix= $product->getPrice() * 100;
            $product->setPrice($prix);
            
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit modifié !');

            $this->redirectToRoute('admin_products_index');

        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView()
        ]);
        // return $this->render('admin/products/index.html.twig');
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