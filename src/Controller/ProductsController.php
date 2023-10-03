<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Products;
use App\Form\CommentsFormType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    
    #[Route('/{slug}', name: 'details')]
    public function details(Products $product, CommentsRepository $commentsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $comments = $commentsRepository->findByProducts($product->getId());
        $comment = new Comments();
        
        $form = $this->createForm(CommentsFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $comment->setContent($form->get('content')->getData())
                    ->setProduct($product)
                    ->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('products_details', [
                'slug' => $product->getSlug()
            ]);
        }



        return $this->render('products/details.html.twig', [
            'commentForm' => $form->createView(),
            'product' => $product,
            'comments' => $comments
        ]);
    }
}
