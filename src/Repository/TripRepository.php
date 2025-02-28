<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripUtil;
use App\Service\DistanceService;
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
        TripUtil::byUser($qb, $user);
        TripUtil::byDateBetween($qb, $dateFrom, $dateTo);
        TripUtil::orderByDate($qb);
        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Search in Trip with fields of Search page
     * Used in : search
     * @return Trip[] Returns an array of Trip objects
     */
    public function findBySearchFields(
        User|null $user,
        Activity $activity = null,
        \DateTimeImmutable $dateFrom = new \DateTimeImmutable('today'),
        \DateTimeImmutable $dateTo = new \DateTimeImmutable('today + 7 day'),
        string $location = null,
        float $lat = null,
        float $lng = null,
        int $distance = 30,
        bool $isFriend = false,
    ): array {
        // Create Query builder
        $qb = $this->createQueryBuilder('t');
        if ($user) {
            // Different from the user
            TripUtil::isNotUser($qb, $user);
            // Trip not already asked by user
            TripUtil::isNotAsked($qb, $user);
            // Different from blocked users
            TripUtil::isNotBlockedUsers($qb, $user);
            // In Followed users
            if ($isFriend == true) {
                TripUtil::byFriendUsers($qb, $user);
            }
        }
        // Is available
        TripUtil::isAvailable($qb, true);
        // Between dates
        TripUtil::byDateBetween($qb, $dateFrom, $dateTo);
        // Equal to activity
        if ($activity) {
            TripUtil::byActivity($qb, $activity);
        }
        // Locations, single or square
        if ($location) {
            TripUtil::byLocation($qb, $location, $lat, $lng, $distance);
        }
        // Orderby Date
        TripUtil::orderByDate($qb);

        $results = $qb->getQuery()
            ->getResult();
        // Delete results in corners if square search
        if ($location && $distance) {
            return  TripUtil::checkDistance($results, $lat, $lng, $distance);
        }
        return $results;
    }

    /**
     * Count total number of trip
     * Used in : homepage, search
     * @return int Returns an integer
     */
    public function countTotalTrips(User $user = null): int
    {
        $qb = $this->createQueryBuilder('t');
        if ($user) {
            TripUtil::isNotUser($qb, $user);
        }
        TripUtil::byDateBetween($qb);

        $result = $qb->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    /**
     * Trips before or later than today
     * @param compare < >=
     * 
     * @return Event[] Returns an array of Event objects
     */
    public function findByPeriod($compare): array
    {
        $today = new \DateTimeImmutable('today');

        return $this->createQueryBuilder('t')
            // A partir de $today
            ->andWhere('t.dateAt ' . $compare . ' :date')
            ->setParameter('date', $today)
            // triés par date
            ->orderBy('t.dateAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a trip by content in title
     * @return Trip[] Returns an array of Trip objects
     */
    public function findByTitle(string $value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.title LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
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
