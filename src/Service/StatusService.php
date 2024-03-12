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
        // Table to count each type of status for trip
        $status = [
            TripRequest::OWNER => ['qty' => 0],
            TripRequest::PENDING => ['qty' => 0],
            TripRequest::ACCEPTED => ['qty' => 0],
            TripRequest::REFUSED => ['qty' => 0]
        ];

        $tripStatus = [];
        foreach ($trips as $trip) {
            // Initialise status for trip
            $tripStatus[$trip->getId()] = $status;


            foreach ($trip->getTripRequests() as $tr) {
                // Count only for requests
                if ($tr->getStatus() != TripRequest::OWNER) {
                    $tripStatus[$trip->getId()][$tr->getStatus()]['qty'] += 1;
                    $tripStatus[$trip->getId()][$tr->getStatus()]['color'] = $tr->getColor();
                }
            }
        }

        return $tripStatus;
    }
}
