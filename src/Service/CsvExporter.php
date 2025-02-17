<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CsvExporter
{
    public function exportProducts(array $products): string
    {
        // Ouais bon merci stack overflow
        $handle = fopen('php://temp', 'r+');
        
        fputcsv($handle, ['Name', 'Description', 'Price', 'Stock', 'Character', 'Size', 'ImageName']);
    
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice(),
                $product->getStock(),
                $product->getCharacter(),
                $product->getSize(),
                $product->getImageFilename()
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return $content;
    }
}
