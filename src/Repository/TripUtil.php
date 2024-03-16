<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Activity;
use App\Entity\TripRequest;
use Doctrine\ORM\QueryBuilder;
use App\Service\DistanceService;

/**
 *
 * Utilities functions for Trip search
 *
 */
class TripUtil
{
    /**
     * Search in Trip by date between
     *
     * @param QueryBuilder $qb
     * @param \DateTimeImmutable|null $dateStart, start today
     * @param \DateTimeImmutable|null $dateEnd, if null end today + 7 days
     * @return QueryBuilder
     */
    public static function byDateBetween(
        QueryBuilder $qb,
        \DateTimeImmutable|null $dateFrom = null,
        \DateTimeImmutable|null $dateTo = null
    ): QueryBuilder {
        if ($dateFrom === null) $dateFrom = new \DateTimeImmutable("today");

        if ($dateTo === null) $dateTo = new \DateTimeImmutable($dateFrom->format('Y-m-d') . ' + 7 days');

        return $qb->andWhere('t.dateAt BETWEEN :dateFrom AND :dateTo')
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo);
    }

    /**
     * Equal to User
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function byUser(
        QueryBuilder $qb,
        User $user
    ): QueryBuilder {
        return $qb->andWhere('t.member = :member')
            ->setParameter('member', $user);
    }

    /**
     * Different from the user
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function isNotUser(QueryBuilder $qb, User $user): QueryBuilder
    {
        return $qb->andWhere('t.member != :member')
            ->setParameter('member', $user);
    }

    /**
     * If user haven't a trip request on the trip
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function isNotAsked(QueryBuilder $qb, User $user): QueryBuilder
    {
        $trs = $user->getTripRequests();
        foreach ($trs as $k => $tr) {
            $qb->andWhere(
                $qb->expr()->not(
                    $qb->expr()->isMemberOf(':tr' . $k, 't.tripRequests')
                )
            )
                ->setParameter('tr' . $k, $tr);
        }
        return $qb;
    }

    /**
     * Order results by date
     *
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    public static function orderByDate(
        QueryBuilder $qb
    ): QueryBuilder {
        return $qb->orderBy('t.dateAt', 'ASC');
    }

    /**
     * Is Available
     *
     * @param QueryBuilder $qb
     * @param boolean $bool
     * @return QueryBuilder
     */
    public static function isAvailable(QueryBuilder $qb, bool $bool): QueryBuilder
    {
        return $qb->andWhere('t.isAvailable = :bool')
            ->setParameter('bool', $bool);
    }

    /**
     * Equal to activity
     *
     * @param QueryBuilder $qb
     * @param Sport $sport
     * @return QueryBuilder
     */
    public static function byActivity(QueryBuilder $qb, Activity $activity): QueryBuilder
    {
        return $qb->andWhere('t.activity = :activity')
            ->setParameter('activity', $activity);
    }

    // Locations
    public static function byLocation(
        QueryBuilder $qb,
        string $location,
        float $lat,
        float $lng,
        int $distance = null
    ): QueryBuilder {
        // Single location search
        if (!$distance) {
            return $qb->andWhere('t.location = :location')
                ->setParameter('location', $location);
        }

        // Calculate square search
        $coords = DistanceService::cardinalCoordonatesDistanceFromPoint(
            $lat,
            $lng,
            $distance
        );

        // Square location search
        $qb->andWhere(
            $qb->expr()->andX(
                $qb->expr()->between('t.lat', $coords['S']['latitude'], $coords['N']['latitude']),
                $qb->expr()->between('t.lng', $coords['W']['longitude'], $coords['E']['longitude'])
            )
        );

        return $qb;
    }

    /**
     * Trip Owner is NOT in blocked users of user
     * user is NOT in blocked users of Trip Owner
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function isNotBlockedUsers(QueryBuilder $qb, User $user): QueryBuilder
    {
        $blocked = [];
        // Users I blocked and users blocked me
        $blockedUsers = $user->getMyBlocked();
        $blockedMe = $user->getBlockedWithMe();
        // Break if no blocked users
        if ($blockedUsers->isEmpty() && $blockedMe->isEmpty()) {
            return $qb;
        }
        // Fill blocked with users i block and with users block me
        foreach ($blockedUsers as $b) {
            $blocked[] = $b->getFriend();
        }
        foreach ($blockedMe as $b) {
            $blocked[] = $b->getMember();
        }

        return $qb->andWhere(
            $qb->expr()->notIn('t.member', ':blocked')
        )
            ->setParameter('blocked', $blocked);
    }

    /**
     * Trip Owner in following users of user
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function byFriendUsers(QueryBuilder $qb, User $user): QueryBuilder
    {
        $friends = [];
        $friendUsers = $user->getMyFriends();
        if ($friendUsers->isEmpty()) {
            return $qb;
        }
        // Fill table of friends
        foreach ($friendUsers as $friendUser) {
            $friends[] = $friendUser->getFriend()->getId();
        }

        return $qb->andWhere(
            $qb->expr()->in('t.member', ':friends')
        )
            ->setParameter('friends', $friends);
    }

    // Delete trips in corners of square search, where distance > $distance
    public static function checkDistance(array $results, float $lat, float $lng, int $distance): array
    {
        $trips = [];
        foreach ($results as $trip) {
            $dist = DistanceService::distanceInKmBetweenEarthCoordinates(
                $trip->getLat(),
                $trip->getLng(),
                $lat,
                $lng
            );

            if ($dist < $distance) {
                $trips[] = $trip;
            }
        }
        return $trips;
    }
}
