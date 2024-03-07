<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TripRepository;
use App\Service\PlanningService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning_index')]
    public function index(
        TripRepository $tripRepository,
        PlanningService $planningService
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $trips = $tripRepository->findByUserAndBetweenDate($user);

        dump($trips);

        return $this->render('planning/index.html.twig', [
            'calendar' => $planningService->getPlanning($trips),
        ]);
    }
}
