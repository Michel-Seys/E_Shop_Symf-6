<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]

    public function list(Categories $category, ProductsRepository $productsRepository, Request $request): Response
    {
        //On cherche le numero de page dans l'url
        $page = $request->query->getInt('page', 1);
        //Liste des produits de la categorie
        $product = $productsRepository->findProductsPaginated($page, $category->getSlug(), 3);
        return $this->render('categories/list.html.twig', compact('category', 'product'));
    }


}
