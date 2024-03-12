<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\TripRequest;
use App\Service\StatusService;
use App\Service\PlanningService;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning_index')]
    public function index(
        TripRepository $tripRepository,
        TripRequestRepository $tripRequestRepository,
        PlanningService $planningService,
        StatusService $statusService,
        Request $request
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Filter status
        switch ($request->get('f')) {
            case 'created':
                $filterStatus = [TripRequest::OWNER];
                break;
            case 'requests':
                $filterStatus = [TripRequest::ACCEPTED, TripRequest::PENDING, TripRequest::REFUSED];
                break;
            default:
                $filterStatus = [TripRequest::ACCEPTED, TripRequest::OWNER, TripRequest::PENDING, TripRequest::REFUSED];
                break;
        }

        // Looking for join requests where user its involved for planning
        $tripRequests = $tripRequestRepository->findByUserAndBetweenDateAndStatus($user, null, null, $filterStatus);

        $tripRequestsStatus = [];
        if ($request->get('f') != 'requests') {
            // Get trip requests of user
            $createdTrips = $tripRepository->findByUserAndBetweenDate($user);
            // Get status for each trip created by user
            $tripRequestsStatus = $statusService->getStatus($createdTrips);
        }

        return $this->render('planning/index.html.twig', [
            'calendar' => $planningService->getPlanning($tripRequests),
            'status' => $tripRequestsStatus,
        ]);
    }
}
