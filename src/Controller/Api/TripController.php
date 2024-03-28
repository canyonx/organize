<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\DateService;
use App\Repository\TripRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    #[Route('/api/alltripthatday', methods: ['GET', 'POST'], name: 'api_all_trip_that_day')]
    public function getAllTripThatDay(
        Request $request,
        TripRepository $tripRepository,
        SerializerInterface $serializer,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Get POST data from axios
        $postData = json_decode($request->getContent());
        $location = $postData->location;
        $date = $postData->date;
        $lat = $postData->lat;
        $lng = $postData->lng;
        $distance = $postData->distance;

        $dateFrom = new \DateTimeImmutable($date);
        $dateTo = new \DateTimeImmutable($date . ' + 1 day');

        $trips = $tripRepository->findBySearchFields(
            user: $this->getUser(),
            location: $location,
            dateFrom: $dateFrom,
            dateTo: $dateTo,
            lat: $lat,
            lng: $lng,
            distance: $distance,
        );

        $trips = $serializer->serialize($trips, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'title', 'lat', 'lng', 'activity' => ['name']]]);


        return $this->json($trips);
    }
}
