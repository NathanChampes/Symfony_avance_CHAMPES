<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllOrderedByPriceDesc(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.price', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllSorted(string $sort = 'name', string $direction = 'asc'): array
    {
        $validFields = ['name', 'price', 'stock'];
        $sort = in_array($sort, $validFields) ? $sort : 'name';
        $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';

        return $this->createQueryBuilder('p')
            ->orderBy('p.' . $sort, $direction)
            ->getQuery()
            ->getResult();
    }
}
