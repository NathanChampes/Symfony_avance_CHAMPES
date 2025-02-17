<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Ce n'est pas réellement un produit "en vedette", mais on va dire que c'est le cas pour la science
        $featuredProducts = $entityManager->getRepository(Product::class)->findAllOrderedByPriceDesc();

        // On ne garde que les 3 premiers produits
        $featuredProducts = array_slice($featuredProducts, 0, 3);

        return $this->render('home/home.html.twig', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}