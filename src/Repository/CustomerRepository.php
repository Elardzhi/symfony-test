<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
    * @return Customer[] Returns an array of Customer objects
    */
    public function findAllNotDeleted()
    {
        return $this->getQueryBuilderNotDeleted()
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByUuid($uuid): ?Customer
    {
        return $this->getQueryBuilderNotDeleted()
            ->andWhere('c.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function getQueryBuilderNotDeleted()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deletedAt IS NULL');
    }

}
