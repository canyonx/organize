<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\TripRequest;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /**
     * @param user User
     * @param dateStart DateTime, start date 
     * @param status Array of join::STATUS 
     * @param plusDays Int, days to add
     * 
     * @return Join[] Returns an array of Join objects
     */
    public function findByUserAndBetweenDateAndStatus(
        User $user,
        \DateTimeImmutable $dateFrom = null,
        \DateTimeImmutable $dateTo = null,
        array $status
    ): array {

        // Set default dates
        if ($dateFrom === null) $dateFrom = new \DateTimeImmutable("today");
        if ($dateTo === null) $dateTo = new \DateTimeImmutable($dateFrom->format('Y-m-d') . ' + 7 days');

        $qb = $this->createQueryBuilder('r');
        // Check Status if corresponding to planning filter
        foreach ($status as $key => $value) {
            $qb->orWhere('r.status = :value' . $key)
                ->setParameter('value' . $key, $value);
        }
        // trip request user is user
        $qb->andWhere('r.member = :member')
            ->setParameter('member', $user);
        // Trip date between
        $qb->leftJoin('r.trip', 't')
            ->andWhere('t.dateAt BETWEEN :dateFrom AND :dateTo')
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo);

        // // Query at same time reduce nb of db queries
        // // Trip
        // $qb->addSelect('trip')
        //     // Trip User
        //     ->leftJoin('trip.user', 'tripOwner')
        //     ->addSelect('tripOwner')
        //     // Trip join requests
        //     ->leftJoin('trip.joins', 'tripJoins')
        //     ->addSelect('tripJoins')
        //     // Join user
        //     ->leftJoin('j.user', 'joinOwner')
        //     ->addSelect('joinOwner')
        //     // Join messages
        //     ->leftJoin('j.messages', 'message')
        //     ->addSelect('message');

        // Order by Date
        $qb->addOrderBy('t.dateAt', 'ASC');
        // dd($qb->getDQL());
        return $qb->getQuery()
            ->getResult();
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
