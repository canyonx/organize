<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\TripRequest;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/trip/request')]
class TripRequestController extends AbstractController
{
    #[Route('/{id}', name: 'app_trip_request_show')]
    public function show(
        TripRequest $tripRequest = null,
        Request $request,
        MessageRepository $messageRepository,
        EntityManagerInterface $em
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // TripRequest not found
        // User is not the triprequest user or not the trip user
        if (!$tripRequest || (($tripRequest->getMember() || $tripRequest->getTrip()->getMember()) != $user)) {
            throw new AccessDeniedException();
        }

        $trip = $tripRequest->getTrip();

        if ($request->get('status')) {
            $this->denyAccessUnlessGranted('TRIP_OWNER', $trip); // Deny if not owner of the trip
            $tripRequest->setStatus($request->get('status'));
            $em->flush();
            //* TripRequestListener : postUpdate send new status change notification
        }

        // Get messages from tripR$tripRequest request
        $discution = $messageRepository->findBy(['tripRequest' => $tripRequest], ['createdAt' => 'ASC']);

        // Add isRead to true when opening join request
        foreach ($discution as $message) {
            if ($message->getMember() != $user) $message->setIsRead(true);
            $em->persist($message); // Comment line for notif test
        }
        $em->flush();

        // Create new message and message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTimeImmutable('now'))
                ->setMember($user)
                ->setTripRequest($tripRequest)
                ->setIsRead(false);
            $em->persist($message);
            $em->flush();
            //* MessageListener : postPersist send new Message Notification

            return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip_request/show.html.twig', [
            'tripRequest' => $tripRequest,
            'trip' => $trip,
            'discution' => $discution,
            'form' => $form
        ]);
    }

    // #[Route('/status/{id}', name: 'app_trip_request_status', methods: ['GET', 'POST'])]
    // public function edit(
    //     Request $request,
    //     TripRequest $tripRequest,
    //     EntityManagerInterface $em,
    //     TranslatorInterface $translator,
    //     NotificationService $notificationService
    // ): Response {
    //     if (!$tripRequest) {
    //         return $this->redirectToRoute('app_planning_index');
    //     }

    //     // Deny if not owner of the trip
    //     $this->denyAccessUnlessGranted('TRIP_OWNER', $tripRequest->getTrip());

    //     /** @var User */
    //     $user = $this->getUser();

    //     $tripRequest->setStatus($request->get('status'));
    //     $em->flush();
    //     // JoinEventListener : send mail on update, status change

    //     $to = ($user == $tripRequest->getMember()) ? $tripRequest->getTrip()->getMember() : $tripRequest->getMember();

    //     // If $to user setting isIsNewMessage
    //     if ($to->getSetting() && $to->getSetting()->isIsTripRequestStatusChange()) {
    //         // ! Status Change Notification
    //         $notificationService->send(
    //             $to,
    //             [
    //                 'title' => 'Changement de status pour ' . $tripRequest->getTrip()->getTitle(),
    //                 'message' => 'Votre demande à maintenant le status ' . $translator->trans(ucfirst(strtolower($tripRequest->getStatus())))
    //             ]
    //         );
    //     }
    //     return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()]);
    // }

    #[Route('/delete/{id}', name: 'app_trip_request_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        TripRequest $tripRequest,
        EntityManagerInterface $em
    ): Response {
        // Cannot delete trip request with a refused status
        if ($tripRequest->getStatus() != TripRequest::REFUSED) {
            if ($this->isCsrfTokenValid('delete' . $tripRequest->getId(), $request->request->get('_token'))) {
                $em->remove($tripRequest);
                $em->flush();
            }
        } else {
            $this->addFlash('warning', '<i class="fa-solid fa-circle-xmark fa-xl"></i> Impossible de supprimer une demande refusée');
        }

        return $this->redirectToRoute('app_trip_show', ['id' => $tripRequest->getTrip()->getId()], Response::HTTP_SEE_OTHER);
    }
}
