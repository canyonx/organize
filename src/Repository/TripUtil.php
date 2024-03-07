<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

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
     * @param \DateTimeImmutable|null $dateStart, if null start today
     * @param \DateTimeImmutable|null $dateEnd, if null end today + 7 days
     * @return QueryBuilder
     */
    public static function ByDateBetween(
        QueryBuilder $qb,
        \DateTimeImmutable $dateFrom = null,
        \DateTimeImmutable $dateTo = null
    ): QueryBuilder {
        if ($dateFrom === null) {
            $dateFrom = new \DateTimeImmutable("today");
        }

        if ($dateTo === null) {
            $dateTo = new \DateTimeImmutable($dateFrom->format('Y-m-d') . ' + 7 days');
        }

        return $qb->andWhere('t.dateAt >= :dateFrom')
            ->setParameter('dateFrom', $dateFrom)
            ->andWhere('t.dateAt <= :dateTo')
            ->setParameter('dateTo', $dateTo);
    }

    /**
     * Search in Trip by User
     *
     * @param QueryBuilder $qb
     * @param User $user
     * @return QueryBuilder
     */
    public static function ByUser(
        QueryBuilder $qb,
        User $user
    ): QueryBuilder {
        return $qb->andWhere('t.member = :member')
            ->setParameter('member', $user);
    }

    /**
     * Order results by date
     *
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    public static function OrderByDate(
        QueryBuilder $qb
    ): QueryBuilder {
        return $qb->orderBy('t.dateAt', 'ASC');
    }
}
