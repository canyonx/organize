<?php

namespace App\Controller\Api;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    /**
     * Check if user already have a trip for that day
     *
     * @return Json bool
     */
    #[Route('/api/istripthatday/{id}/{date<\d{4}-\d{2}-\d{2}>}', methods: ['GET'], name: 'api_is_trip_that_day')]
    public function getIsTripThatDay(
        Trip $trip,
        string $date,
        TripRepository $tripRepository,
        TripRequestRepository $tripRequestRepository
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $dateFrom = new \DateTimeImmutable($date);
        $dateTo = new \DateTimeImmutable($date . ' + 1 day');

        // Looking for user trip for a day
        $trips = $tripRepository->findByUserAndBetweenDate($user, $dateFrom, $dateTo);
        // Remove trip from the array trips
        if (($key = array_search($trip, $trips)) !== false) {
            unset($trips[$key]);
        }



        dump($dateFrom, $dateTo, $trips);
        // Looking for user trip request for a day
        // $tr = $tripRequestRepository->findOneBy(['member' => $user, 'dateAt' => $date]);

        $alreadyTrip = ($trips) ? true : false;

        return $this->json($alreadyTrip);
    }
}
