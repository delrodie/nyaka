<?php

namespace App\Repository;

use App\Entity\Aspirant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param $grade
     * @return mixed
     */
    public function getAllByGrade($grade, $status = null): mixed
    {
        $query = $this->createQueryBuilder('a')
            ->addSelect('s')
            ->addSelect('d')
            ->addSelect('v')
            ->addSelect('g')
            ->leftJoin('a.section', 's')
            ->leftJoin('s.doyenne', 'd')
            ->leftJoin('d.vicariat', 'v')
            ->leftJoin('a.grade', 'g')
            ->where('g.id = :grade')
            ->setParameter('grade', $grade);

        if ($status){
            $query->andWhere('a.wave_checkout_status = :status')
                ->setParameter('status', $status);
        }


        return $query->getQuery()->getResult();
    }

    public function getMontantTotal()
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.montant)')
            ->where('a.wave_checkout_status = :status')
            ->setParameter('status', 'complete')
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * Liste des participants ayant effectué le paiement ou non
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllByStatusCompletedOrNot($status = null, $order = null)
    {
        $query = $this->globalSelect();
        if ($status){
            $query->where('a.wave_checkout_status = :status');
        }
        else{
            $query->where('a.wave_checkout_status <> :status');
        }

        if ($order) $query->orderBy('a.wave_when_completed', $order);

        return $query->setParameter('status', 'complete')
            ->getQuery()->getResult() ;


    }

    public function getAllByVicariat($vicariat, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('v.id = :vicariat')
            ->setParameter('vicariat', $vicariat);

        if ($status){
            $query->andWhere('a.wave_checkout_status = :status')
                ->setParameter('status', $status);
        }

        return $query->getQuery()->getResult();
    }

    public function getAllByDoyenne($doyenne, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('d.id = :doyenne')
            ->setParameter('doyenne', $doyenne);

        if ($status){
            $query->andWhere('a.wave_checkout_status = :status')
                ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();
    }

    public function getAllBySection($section, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('s.id = :section')
            ->setParameter('section', $section);

        if ($status){
            $query->andWhere('a.wave_checkout_status = :status')
                ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();
    }

    private function globalSelect()
    {
        return $this->createQueryBuilder('a')
            ->addSelect('s', 'd', 'v', 'g')
            ->leftJoin('a.section', 's')
            ->leftJoin('s.doyenne', 'd')
            ->leftJoin('d.vicariat', 'v')
            ->leftJoin('a.grade', 'g');
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
