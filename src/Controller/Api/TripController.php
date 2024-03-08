<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    #[Route('/api/istripthatday/{dateAt<\d{4}-\d{2}-\d{2}>}', methods: ['GET'], name: 'api_is_trip_that_day')]
    public function getIsTripThatDay(
        string $dateAt,
        TripRepository $tripRepository,
        TripRequestRepository $tripRequestRepository
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $dateFrom = new \DateTimeImmutable($dateAt);
        $dateTo = new \DateTimeImmutable($dateAt . ' + 1 day');

        // Looking for user trip for a day
        $trip = $tripRepository->findByUserAndBetweenDate($user, $dateFrom, $dateTo);

        dump($dateFrom, $dateTo, $trip);
        // Looking for user trip request for a day
        // $tr = $tripRequestRepository->findOneBy(['member' => $user, 'dateAt' => $date]);

        $alreadyTrip = ($trip) ? true : false;

        return $this->json($alreadyTrip);
    }
}
