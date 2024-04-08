<?php

namespace App\Repository;

use App\Entity\Signalment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Signalment>
 *
 * @method Signalment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signalment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signalment[]    findAll()
 * @method Signalment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signalment::class);
    }

    //    /**
    //     * @return Signalment[] Returns an array of Signalment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Signalment
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
