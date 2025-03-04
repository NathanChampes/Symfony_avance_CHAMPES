<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\CsvExporter;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
  // J'ai fait une fonction de test pour l'export des produits quoi
  public function testExportProducts(): void
  {
    $product = new Product();
    $product->setName('Test Product')
      ->setDescription('Test Description')
      ->setPrice(10.0)
      ->setStock(100)
      ->setCharacter('Test Character')
      ->setSize('medium')
      ->setImageFilename('test.png');

    $csvExporter = new CsvExporter();
    $csvContent = $csvExporter->exportProducts([$product]);

    $expectedCsv = "Name,Description,Price,Stock,Character,Size,ImageName\n" .
      "\"Test Product\",\"Test Description\",10,100,\"Test Character\",medium,test.png\n";

    $this->assertEquals($expectedCsv, $csvContent);
  }
}
