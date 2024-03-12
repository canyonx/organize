<?php

namespace App\Controller\Api;

use App\Entity\User;
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
        TripRequestRepository $tripRequestRepository
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Get POST data from axios
        $postData = json_decode($request->getContent());
        $id = $postData->id;
        $date = $postData->date;

        // If no date defined return false
        if (!$date) {
            return $this->json(false);
        }

        $dateFrom = new \DateTimeImmutable($date);
        $dateTo = new \DateTimeImmutable($date . ' + 1 day');

        // Looking for user trip for a day
        $trips = $tripRepository->findByUserAndBetweenDate($user, $dateFrom, $dateTo);
        $tripRequests = $tripRequestRepository->findByUserAndBetweenDate($user, $dateFrom, $dateTo);

        // if id set, editing trip
        if ($id) {
            // Remove the trip from array
            $trip = $tripRepository->find($id);
            if (($key = array_search($trip, $trips)) !== false) {
                unset($trips[$key]);
            }
        }

        // dump($dateFrom, $dateTo, $trips);

        $alreadyTrip = ($trips || $tripRequests) ? true : false;

        return $this->json($alreadyTrip);
    }
}
