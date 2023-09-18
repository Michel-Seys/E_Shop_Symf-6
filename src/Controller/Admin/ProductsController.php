<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
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
            //On récupère les images
            $images = $productForm->get('images')->getData();
            // dd($images);
            foreach($images as $image){
                $folder = 'product';

                $file = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($file);
                $product->addImage($img);
            }

            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $prix= $product->getPrice() * 100;
            $product->setPrice($prix);
            
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté !');

            return $this->redirectToRoute('admin_products_index');

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
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        // $prix= $product->getPrice() / 100;
        // $product->setPrice($prix);
        
        //Creation formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        //Traitement de la requete
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid())
        {
                //On récupère les images
                $images = $productForm->get('images')->getData();
                // dd($images);
                foreach($images as $image){
                    $folder = 'product';
    
                    $file = $pictureService->add($image, $folder, 300, 300);
    
                    $img = new Images();
                    $img->setName($file);
                    $product->addImage($img);
                }
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            
            // $prix= $product->getPrice() * 100;
            // $product->setPrice($prix);
            
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit modifié !');

            return$this->redirectToRoute('admin_products_index');

        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
        // return $this->render('admin/products/index.html.twig');
    }
   

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Products $product): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);

        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/delete/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Images $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'product', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    

}