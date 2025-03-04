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

        // les trucs des guillemets à la fin c'est a cause d'une deprecation ou il faut gérer le export
        fputcsv($handle, ['Name', 'Description', 'Price', 'Stock', 'Character', 'Size', 'ImageName'], ',', '"', '\\');

        // les trucs des guillemets à la fin c'est a cause d'une deprecation ou il faut gérer le export
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice(),
                $product->getStock(),
                $product->getCharacter(),
                $product->getSize(),
                $product->getImageFilename()
            ], ',', '"', '\\');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }
}
