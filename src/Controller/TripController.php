<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Entity\Message;
use App\Entity\TripRequest;
use App\Service\DateService;
use App\Form\TripRequestType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/trip')]
class TripController extends AbstractController
{
    /**
     * Create a new trip
     */
    #[Route('/nouveau', name: 'app_trip_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        DateService $dateService
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $trip = new Trip();
        $trip->setMember($user)
            ->setIsAvailable(true)
            ->setDateAt(new \DateTimeImmutable());

        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // In case user pass the js limit
            if ($dateService->isTripThatDay($user, new \DateTimeImmutable($trip->getDateAt()->format('Y-m-d')))) {
                $this->addFlash('danger', '<i class="fa-solid fa-circle-xmark fa-xl"></i> Une sortie est déjà prévue le ' . $trip->getDateAt()->format('d/m/Y') . ' !!');
                return $this->redirectToRoute('app_trip_new');
            }

            $em->persist($trip);
            $em->flush();
            $this->addFlash('success', '<i class="fa-solid fa-circle-check fa-xl"></i> Sortie crée');
            //* TripListener : send new tripnotification to each user who follow trip member

            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    /**
     * Show trip, public page
     */
    #[Route('/{id}', name: 'app_trip_show', methods: ['GET', 'POST'])]
    public function show(
        Trip $trip,
        DateService $dateService,
        Request $request,
        EntityManagerInterface $em,
        TripRequestRepository $tripRequestRepository
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // Trip not found
        if (!$trip) {
            throw $this->createNotFoundException('Trip not found');
        }

        // User already ask to join the trip -> redirect to trip request show
        $tripRequest = $tripRequestRepository->findOneBy(
            [
                'trip' => $trip,
                'member' => $user,
                'status' => [TripRequest::ACCEPTED, TripRequest::PENDING, TripRequest::REFUSED]
            ],
            []
        );
        if ($tripRequest) {
            return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()]);
        }

        // Check if user have already planified a trip for that day
        // If true show Info subscribe
        $dateFrom = new \DateTimeImmutable($trip->getDateAt()->format('Y-m-d'));
        // If userTripThatDay or userTripRequestThatDay -> show warning limitation
        $alreadyTrip = $dateService->isTripThatDay($user, $dateFrom) ?: false;

        // new Trip Request
        $tr = new TripRequest();
        $tr->setStatus(TripRequest::PENDING)
            ->setTrip($trip)
            ->setMember($this->getUser());

        // Create form for TripRequest, message 
        $form = $this->createForm(TripRequestType::class, $tr);
        $form->handleRequest($request);

        // Form submitted AND NO trip that day
        if ($form->isSubmitted() && $form->isValid()) {
            // Write trip request in DB
            $em->persist($tr);

            // Create message associate to the trip request
            if ($form->get('message')->getData()) {
                $message = new Message();
                $message->setCreatedAt(new \DateTimeImmutable('now'))
                    ->setTripRequest($tr)
                    ->setContent($form->get('message')->getData())
                    ->setMember($user)
                    ->setIsRead(false);

                $em->persist($message);
                $this->addFlash('success', '<i class="fa-solid fa-circle-check fa-xl"></i> Demande envoyée à ' . $trip->getMember());
            }
            $em->flush();
            //* TripRequestListener : postPersist send new TR notification

            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()], Response::HTTP_SEE_OTHER);
            // return $this->redirectToRoute('app_trip_request_show', ['id' => $tr->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'tripRequests' => $tripRequestRepository->findBy(
                [
                    'trip' => $trip,
                    'status' => [TripRequest::ACCEPTED, TripRequest::PENDING, TripRequest::REFUSED]
                ],
                [
                    'status' => 'ASC'
                ]
            ),
            'form' => $form,
            'alreadyTrip' => $alreadyTrip
        ]);
    }

    /**
     * Edit trip for the owner
     */
    #[Route('/{id}/modifier', name: 'app_trip_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Trip $trip,
        EntityManagerInterface $em,
        DateService $dateService
    ): Response {
        $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);

        /* @var User */
        $user = $this->getUser();

        $tripDayOrigin = $trip->getDateAt()->format('Y-m-d');

        $form = $this->createForm(TripType::class, $trip, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // In case user pass the js limit
            // If new date is different from date origin
            $tripDayNew = $trip->getDateAt()->format('Y-m-d');
            if ($tripDayOrigin != $tripDayNew) {
                // Check for an existing activity
                if ($dateService->isTripThatDay($user, new \DateTimeImmutable($tripDayNew))) {
                    $this->addFlash('danger', '<i class="fa-solid fa-circle-xmark fa-xl"></i> Une sortie est déjà prévue le ' . $trip->getDateAt()->format('d/m/Y') . ' !!');
                    return $this->redirectToRoute('app_trip_edit', ['id' => $trip->getId()]);
                }
            }

            $em->flush();

            $this->addFlash('success', '<i class="fa-solid fa-circle-check fa-xl"></i> Sortie modifiée');

            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    /**
     * Change availability of a trip
     */
    #[Route('/{id}/available/{param}', name: 'app_trip_available', methods: ['GET'])]
    public function available(
        Trip $trip,
        bool $param,
        EntityManagerInterface $em
    ): Response {
        if ($this->getUser() !== $trip->getMember()) {
            return $this->redirectToRoute('app_planning_index');
        }

        $trip->setIsAvailable($param);
        $em->flush();

        return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * Delete trip for trip owner connected user
     */
    #[Route('/{id}/delete', name: 'app_trip_delete', methods: ['POST'])]
    public function delete(Request $request, Trip $trip, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);

        if ($this->isCsrfTokenValid('delete' . $trip->getId(), $request->request->get('_token'))) {
            $em->remove($trip);
            $em->flush();

            $this->addFlash('success', '<i class="fa-solid fa-circle-check fa-xl"></i> Sortie supprimée');
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
