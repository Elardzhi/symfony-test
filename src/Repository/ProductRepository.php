<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findAllNotDeleted()
    {
        return $this->getQueryBuilderNotDeleted()
            ->getQuery()
            ->getResult();
    }

    private function getQueryBuilderNotDeleted()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.deletedAt IS NULL');
    }

    public function findOneByIssn($issn): ?Product
    {
        return $this->getQueryBuilderNotDeleted()
            ->andWhere('p.issn = :issn')
            ->setParameter('issn', $issn)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllPending(string $range)
    {
        $date = date('Y-m-d H:i:s', strtotime('-' . $range));

        return $this->createQueryBuilder('p')
            ->andWhere("p.status = 'pending'")
            ->andWhere("p.updatedAt <= '$date'")
            ->getQuery()
            ->getResult();
    }
}
