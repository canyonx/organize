<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\DateService;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripController extends AbstractController
{
    /**
     * Check if user already have a trip for that day
     * On create and edit trip
     *
     * @return Json bool
     */
    #[Route('/api/istripthatday', methods: ['GET', 'POST'], name: 'api_is_trip_that_day')]
    public function getIsTripThatDay(
        Request $request,
        TripRepository $tripRepository,
        DateService $dateService
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Get POST data from axios
        $postData = json_decode($request->getContent());
        $id = $postData->id;
        $date = $postData->date;

        // If no date defined return false, new trip
        if (!$date) return $this->json(false);

        $dateFrom = new \DateTimeImmutable($date);

        $alreadyTrip = $dateService->isTripThatDay($user, $dateFrom);

        // If is on edit
        if ($id && $alreadyTrip) {
            $trip = $tripRepository->find($id);

            // user edit trip
            if ($trip->getMember() == $user && $trip->getDateAt()->format('Y-m-d') == $dateFrom->format('Y-m-d')) {
                return $this->json(false);
            }
        }

        return $this->json($alreadyTrip);
    }
}
