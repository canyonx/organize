<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripUtil;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * Find Trips for a User and between dateFrom to dateTo
     *
     * @return Trip[] Returns an array of Trip objects
     */
    public function findByUserAndBetweenDate(
        User $user,
        \DateTimeImmutable $dateFrom = null,
        \DateTimeImmutable $dateTo = null
    ): array {
        $qb = $this->createQueryBuilder('t');
        TripUtil::ByUser($qb, $user);
        TripUtil::ByDateBetween($qb, $dateFrom, $dateTo);
        TripUtil::OrderByDate($qb);
        return $qb->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Trip[] Returns an array of Trip objects
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

    //    public function findOneBySomeField($value): ?Trip
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
