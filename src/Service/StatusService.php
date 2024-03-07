<?php

namespace App\Service;

use App\Entity\Join;
use App\Entity\TripRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatusService extends AbstractController
{
    /**
     * Retourne les status d'un tableau de trips sous forme de tableau
     * [TripId => status[...]]
     * @param join joins where user is the owner
     */
    function getStatus($trips): array
    {
        $status = [
            TripRequest::OWNER => ['qty' => 0, 'color' => TripRequest::COLOR[TripRequest::OWNER]],
            TripRequest::PENDING => ['qty' => 0, 'color' => TripRequest::COLOR[TripRequest::PENDING]],
            TripRequest::ACCEPTED => ['qty' => 0, 'color' => TripRequest::COLOR[TripRequest::ACCEPTED]],
            TripRequest::REFUSED => ['qty' => 0, 'color' => TripRequest::COLOR[TripRequest::REFUSED]]
        ];
        $tripStatus = [];
        foreach ($trips as $trip) {
            // Initialise status for trip
            $tripStatus[$trip->getId()] = $status;
            foreach ($trip->getJoins() as $join) {
                // Count only for requests
                if ($join->getStatus() != Join::OWNER) {
                    $tripStatus[$join->getTrip()->getId()][$join->getStatus()]['qty'] += 1;
                    $tripStatus[$join->getTrip()->getId()][$join->getStatus()]['color'] = TripRequest::COLOR[$join->getStatus()];
                }
            }
        }

        return $tripStatus;
    }
}
