<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\SearchType;
use App\Service\PlanningService;
use App\Repository\TripRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search_index')]
    public function index(
        TripRepository $tripRepository,
        PlanningService $planningService,
        Request $request,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Form Search
        $search = new Trip();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Submitted form 
            // distance from unmapped field of SearchType form
            // default values
            $distance = $form->get('distance')->getData();
            $isFriend = $form->get('isFriend')->getData();
            $trips = $tripRepository->findBySearchFields(
                $user,
                $search->getActivity(),
                $search->getDateAt(),
                $search->getLocation(),
                $distance,
                $isFriend
            );
        } else {
            // Unsubmitted form default values
            $distance = 50;
            $search->setActivity(null)
                ->setDateAt(new \DateTimeImmutable('today'))
                ->setLocation($user->getCity());
            $isFriend = false;
            $trips = $tripRepository->findBySearchFields(
                $user,
                $search->getActivity(),
                $search->getDateAt(),
                $search->getLocation(),
                $distance,
                $isFriend
            );
        }

        dump($isFriend);

        return $this->render('search/index.html.twig', [
            // Trips found
            'trips' => $trips,
            // Search object (trip)
            'search' => $search,
            // Distance value
            // 'distance' => $distance,
            // Calendar trips ordered by date
            'calendar' => $planningService->getPlanning($trips, $search->getDateAt()),
            // Form search
            'form' => $form,
            // Total trips in app
            'totalTrips' => $tripRepository->countTotalTrips($user, (new \DateTime('today')))
        ]);
    }
}
