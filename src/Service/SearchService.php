<?php

namespace App\Service;

use App\Entity\Trip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchService extends AbstractController
{
    public function __construct(
        private DateService $dateService
    ) {
    }

    /**
     * Retourne le tableau complet contenant les sorties
     * depuis fromtime jusqu'à fromtime + days
     * 
     * @param fromtime DateTime, start date 
     * @param days Int, nombre de jours à ajouter 
     * 
     * @return Array date(Y-m-d) => Trip[]
     */
    public function getSearchCalendar(
        $trips,
        \DateTimeImmutable $fromtime = new \DateTimeImmutable('today'),
        int $days = 7,
    ): array {
        // Récupère le tableau des dates 
        $dates = DateService::getDates($fromtime, $days);

        $calendar = [];
        foreach ($dates as $date) {
            $date = $date->format('Y-m-d');
            $calendar[$date] = [];

            // Add trip to Calendar array
            foreach ($trips as $key => $trip) {
                if ($date === $trip->getDateAt()->format('Y-m-d')) {
                    $calendar[$date][$key] = $trip;
                }
            }
        }
        // dump($calendar);
        return $calendar;
    }
}
