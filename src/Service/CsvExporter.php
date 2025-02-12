<?php

namespace App\Service;

class CsvExporter
{
    public function exportProducts(array $products): string
    {
        // Ouais bon merci stack overflow
        $handle = fopen('php://temp', 'r+');
        
        fputcsv($handle, ['Name', 'Description', 'Price']);
    
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return $content;
    }
}
