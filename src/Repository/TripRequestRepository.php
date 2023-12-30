<?php

namespace App\Repository;

use App\Entity\TripRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TripRequest>
 *
 * @method TripRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TripRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TripRequest[]    findAll()
 * @method TripRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TripRequest::class);
    }

//    /**
//     * @return TripRequest[] Returns an array of TripRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TripRequest
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
