<?php

namespace App\Repository;

use App\Entity\Vicariat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vicariat>
 *
 * @method Vicariat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vicariat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vicariat[]    findAll()
 * @method Vicariat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VicariatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vicariat::class);
    }

    //    /**
    //     * @return Vicariat[] Returns an array of Vicariat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vicariat
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
