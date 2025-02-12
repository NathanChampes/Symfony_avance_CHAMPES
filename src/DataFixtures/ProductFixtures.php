<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class ProductFixtures extends Fixture
{
    private $params;
    private $filesystem;

    public function __construct(ParameterBagInterface $params, Filesystem $filesystem)
    {
        $this->params = $params;
        $this->filesystem = $filesystem;
    }

    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Peluche Gromit Classique',
                'character' => 'Gromit',
                'description' => 'Peluche officielle de Gromit dans sa pose emblématique',
                'price' => 29.99,
                'size' => 'medium',
                'stock' => 50,
                'image_filename' => 'gromit-classic.png'
            ],
            [
                'name' => 'Peluche Wallace Inventeur',
                'character' => 'Wallace',
                'description' => 'Peluche Wallace avec sa tenue d\'inventeur et son gilet vert',
                'price' => 34.99,
                'size' => 'large',
                'stock' => 30,
                'image_filename' => 'wallace-inventor.jpg'
            ],
            [
                'name' => 'Mini Peluche Shaun le Mouton',
                'character' => 'Shaun',
                'description' => 'Petite peluche adorable de Shaun le Mouton',
                'price' => 19.99,
                'size' => 'small',
                'stock' => 45,
                'image_filename' => 'shaun-sheep.jpg'
            ],
            [
                'name' => 'Peluche Preston Chien Robot',
                'character' => 'Preston',
                'description' => 'Peluche collector du chien robot Preston',
                'price' => 39.99,
                'size' => 'medium',
                'stock' => 20,
                'image_filename' => 'preston.png'
            ],
            [
                'name' => 'Duo Peluches Wallace & Gromit',
                'character' => 'Wallace & Gromit',
                'description' => 'Set collector avec Wallace et Gromit en tenue de Un Mauvais Pantalon',
                'price' => 49.99,
                'size' => 'large',
                'stock' => 15,
                'image_filename' => 'wallace-gromit-duo.jpg'
            ]
        ];

        $uploadsDir = $this->params->get('products_directory');
        
        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name'])
                   ->setCharacter($productData['character'])
                   ->setDescription($productData['description'])
                   ->setPrice($productData['price'])
                   ->setSize($productData['size'])
                   ->setStock($productData['stock'])
                   ->setImageFilename($productData['image_filename']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}