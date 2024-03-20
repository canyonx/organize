<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\SearchType;
use App\Service\SearchService;
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
        SearchService $searchService,
        Request $request,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Form Search
        $search = new Trip();
        $search->setDateAt(new \DateTimeImmutable('today'));

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Submitted form 
            // distance from unmapped field of SearchType form
            // default values
            $distance = $form->get('distance')->getData() ?: false;
            $isFriend = $form->get('isFriend')->getData();

            $trips = $tripRepository->findBySearchFields(
                $user,
                $search->getActivity(),
                new \DateTimeImmutable('today'),
                new \DateTimeImmutable('today + ' . $this->getParameter('app_planning_week') . ' day'),
                $search->getLocation(),
                $search->getLat(),
                $search->getLng(),
                $distance,
                $isFriend
            );
        } else {
            // Unsubmitted form default values
            $distance = 30;
            $isFriend = false;
            $search->setActivity(null)
                ->setDateAt(new \DateTimeImmutable('today'))
                ->setLocation($user ? $user->getCity() : 'Briançon')
                ->setLat($user ? $user->getLat() : 44.896)
                ->setLng($user ? $user->getLng() : 6.638);

            $trips = $tripRepository->findBySearchFields(
                $user ?: null,
                $search->getActivity(),
                new \DateTimeImmutable('today'),
                new \DateTimeImmutable('today + ' . $this->getParameter('app_planning_week') . ' day'),
                $search->getLocation(),
                $search->getLat(),
                $search->getLng(),
                $distance,
                $isFriend
            );
        }

        return $this->render('search/index.html.twig', [
            // Trips found
            'trips' => $trips,
            // Search object (trip)
            'search' => $search,
            // Distance value
            'distance' => $distance,
            // Calendar trips ordered by date
            'calendar' => $searchService->getSearchCalendar($trips, $search->getDateAt(), $this->getParameter('app_planning_week')),
            // Form search
            'form' => $form,
            // Total trips in app
            'totalTrips' => $tripRepository->countTotalTrips($user, (new \DateTime('today')))
        ]);
    }
}
