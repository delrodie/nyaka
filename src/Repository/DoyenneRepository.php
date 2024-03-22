<?php

namespace App\Repository;

use App\Entity\Doyenne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Doyenne>
 *
 * @method Doyenne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doyenne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doyenne[]    findAll()
 * @method Doyenne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoyenneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doyenne::class);
    }

    //    /**
    //     * @return Doyenne[] Returns an array of Doyenne objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Doyenne
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
