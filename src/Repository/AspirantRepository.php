<?php

namespace App\Repository;

use App\Entity\Aspirant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aspirant>
 *
 * @method Aspirant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aspirant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aspirant[]    findAll()
 * @method Aspirant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AspirantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aspirant::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('a')
            ->addSelect('s')
            ->addSelect('d')
            ->addSelect('v')
            ->addSelect('g')
            ->leftJoin('a.section', 's')
            ->leftJoin('s.doyenne', 'd')
            ->leftJoin('d.vicariat', 'v')
            ->leftJoin('a.grade', 'g')
            ->getQuery()->getResult();
    }

    //    /**
    //     * @return Aspirant[] Returns an array of Aspirant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Aspirant
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
