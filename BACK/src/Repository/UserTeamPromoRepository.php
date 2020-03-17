<?php

namespace App\Repository;

use App\Entity\UserTeamPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserTeamPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTeamPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTeamPromo[]    findAll()
 * @method UserTeamPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTeamPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTeamPromo::class);
    }

    // /**
    //  * @return UserTeamPromo[] Returns an array of UserTeamPromo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTeamPromo
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
