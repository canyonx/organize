<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\SearchMapType;
use App\Repository\TripRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    #[Route('/carte', name: 'app_map_index')]
    public function index(
        Request $request,
        TripRepository $tripRepository,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Form Search
        $search = new Trip();
        $search->setDateAt(new \DateTimeImmutable('today'));

        $form = $this->createForm(SearchMapType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Submitted form 
            // distance from unmapped field of SearchType form
            // default values
            // dump($search);
            $distance = $form->get('distance')->getData() ?: 30;
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

            // return $this->redirectToRoute('app_map_index');
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

        return $this->render('map/index.html.twig', [
            'form' => $form,
            // Search object (trip)
            'search' => $search,
            // Distance value
            'distance' => $distance,
            // Total trips in app
            'totalTrips' => $tripRepository->countTotalTrips($user, (new \DateTime('today')))
        ]);
    }
}
