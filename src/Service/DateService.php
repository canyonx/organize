<?php

namespace App\Service;

use App\Entity\TripRequest;
use DateTime;
use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;

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
        \DateTime $fromtime,
        int $days = 7
    ): array {
        for ($i = 0; $i < $days; $i++) {
            $dates[$i] = new \DateTime($fromtime->format('Y-m-d') . "+ $i day");
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
        DateTime $date
    ): bool {
        // Consider participating to trip if status
        $status = [TripRequest::ACCEPTED, TripRequest::PENDING];
        // Is user have a trip that day
        $myTrips = $this->tripRequestRepository->findByUserDateAndStatus($user, $date, $status, 1);
        if ($myTrips) {
            return true;
        }
        return false;
    }
}
