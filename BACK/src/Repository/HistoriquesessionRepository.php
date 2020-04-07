<?php

namespace App\Repository;

use App\Entity\Historiquesession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Historiquesession|null find($id, $lockMode = null, $lockVersion = null)
 * @method Historiquesession|null findOneBy(array $criteria, array $orderBy = null)
 * @method Historiquesession[]    findAll()
 * @method Historiquesession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriquesessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historiquesession::class);
    }

    // /**
    //  * @return Historiquesession[] Returns an array of Historiquesession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Historiquesession
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
