<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\CsvExporter;
use Symfony\Component\HttpFoundation\HeaderUtils;


// La je défini la route de base pour le controller
#[Route('/product')]
class ProductController extends AbstractController
{
    //index de la route donc /product en raison de la route de base
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('view', new Product());
        
        $sort = $request->query->get('sort', 'name');
        $direction = $request->query->get('direction', 'asc');
        
        $products = $entityManager->getRepository(Product::class)->findAllSorted($sort, $direction);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'currentSort' => $sort,
            'currentDirection' => $direction
        ]);
    }

    // La c'est la logique de création, le plus dur est de gérer l'upload de l'image
    // J'ai du faire des recherches pour trouver comment faire
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('create', new Product());
        
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Ouais il faut que je le fasse
                }

                $product->setImageFilename($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'product.created_successfully');
            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    // La c'est la logique de l'édition, la logique de l'image est la même que pour la création
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('edit', $product);
        
        // On utilise des formulaires pour gérer les données car c'est plus simple
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('products_directory'),
                        $newFilename
                    );
                    
                    if ($product->getImageFilename()) {
                        unlink($this->getParameter('products_directory').'/'.$product->getImageFilename());
                    }
                    
                    $product->setImageFilename($newFilename);
                } catch (FileException $e) {
                    // Ouais il faut que je le fasse
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'product.edited_successfully');
            return $this->redirectToRoute('app_product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    // La c'est la logique de suppression, j'ai du rajouter la suppression de l'image dans le cas ou l'image existe
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        // utilisation du voter
        $this->denyAccessUnlessGranted('delete', $product);
        
        // utilisation du csrf token, j'ai du rajouter le token dans le template twig
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            if ($product->getImageFilename()) {
                unlink($this->getParameter('products_directory').'/'.$product->getImageFilename());
            }
            
            $entityManager->remove($product);
            $entityManager->flush();
        }

        $this->addFlash('success', 'product.deleted_successfully');
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/export', name: 'app_product_export', methods: ['GET'])]
    public function export(
        EntityManagerInterface $entityManager,
        CsvExporter $csvExporter
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $products = $entityManager->getRepository(Product::class)->findAll();
        $content = $csvExporter->exportProducts($products);
        
        $response = new Response($content);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'products.csv'
        );
        
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
    }
}
