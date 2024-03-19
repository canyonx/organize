<?php

namespace App\Service;

use App\Entity\Trip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningService extends AbstractController
{
    /**
     * Return array of dates
     * Array of dates to show on a planning
     * Config duration in days in service.yaml
     * 
     * @param fromtime DateTime, start date 
     * 
     * @return dates[] Array
     */
    public function getArrayDates(
        \DateTimeImmutable $dateFrom = null
    ): array {
        // Date to start planning
        if ($dateFrom === null) $dateFrom = new \DateTimeImmutable('today');
        // Quantity of days to show
        $dayQty = $this->getParameter('app_planning_week');
        // Create array of dates
        for ($i = 0; $i < $dayQty; $i++) {
            $dates[] = new \DateTimeImmutable($dateFrom->format('Y-m-d') . "+ $i day");
        }
        return $dates;
    }

    /**
     * Get a full planning
     *
     * @param array $trips
     * @return array
     */
    public function getPlanning(
        array $tripRequests,
        \DateTimeImmutable $dateFrom = new \DateTimeImmutable('today')
    ): array {
        // Array of dates to show in the planning
        $dates = $this->getArrayDates($dateFrom);

        // Create calendar and organises trips by days
        $calendar = [];
        foreach ($dates as $date) {
            $date = $date->format('Y-m-d');
            $calendar[$date] = [];

            // Add activity to Array
            foreach ($tripRequests as $key => $tr) {
                if ($tr->getTrip()->getDateAt()->format('Y-m-d') == $date) {
                    $calendar[$date][$key] = $tr->getTrip();
                }
            }
        }
        // dump($calendar);
        return $calendar;
    }
}
