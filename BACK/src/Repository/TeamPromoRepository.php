<?php

namespace App\Repository;

use App\Entity\TeamPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeamPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamPromo[]    findAll()
 * @method TeamPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamPromo::class);
    }
    /**
     * @return TeamPromo[] Returns an array of TeamPromo objects
     */
public function teamdechaquestructure($valeur){
    return $this->createQueryBuilder('t')
                ->andWhere('t.structure = :val')
                ->setParameter('val', $valeur)
                ->getQuery()
                ->getResult()
                ;
}
    // /**
    //  * @return TeamPromo[] Returns an array of TeamPromo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamPromo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
