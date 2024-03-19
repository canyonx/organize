<?php

namespace App\Service;

use App\Entity\TripRequest;
use DateTime;
use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use DateTimeImmutable;

class DateService
{
    public function __construct(
        private TripRepository $tripRepository,
        private TripRequestRepository $tripRequestRepository,
    ) {
    }

    /**
     * Return array of dates
     * from fromtime to fromtime + days
     * 
     * @param fromtime DateTime, start date 
     * @param days Int, nombre de jours à ajouter 
     * 
     * @return dates[] Array
     */
    public static function getDates(
        \DateTimeImmutable $fromtime,
        int $days = 7
    ): array {
        for ($i = 0; $i < $days; $i++) {
            $dates[$i] = new \DateTimeImmutable($fromtime->format('Y-m-d') . "+ $i day");
        }
        return $dates;
    }

    /**
     * Check if user already have created a trip same day
     * or if user have a join request pending or accepted same day
     * 
     * @param date DateTime, date to check
     * 
     * @return bool
     */
    public function isTripThatDay(
        User $user,
        DateTimeImmutable $date
    ): bool {
        // Consider participating to trip if status
        $status = [TripRequest::ACCEPTED, TripRequest::PENDING, TripRequest::OWNER];
        $dateFrom = new \DateTimeImmutable($date->format('Y-m-d'));
        $dateTo = new \DateTimeImmutable($date->format('Y-m-d') . ' + 1 day');
        // Is user have a trip that day
        $myTrips = $this->tripRequestRepository->findByUserAndBetweenDateAndStatus($user, $dateFrom, $dateTo, $status);

        // dump($myTrips);
        if ($myTrips) {
            return true;
        }
        return false;
    }
}
