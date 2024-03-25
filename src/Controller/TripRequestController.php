<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\TripRequest;
use App\Service\MailerService;
use App\Service\MailjetService;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
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
        MailerService $mailerService,
        EntityManagerInterface $em,
        MailjetService $mailjet
    ): Response {
        /** @var User */
        $user = $this->getUser();

        // TripRequest not found
        // User is not the triprequest user or not the trip user
        if (!$tripRequest || (($tripRequest->getMember() || $tripRequest->getTrip()->getMember()) != $user)) {
            throw new AccessDeniedException();
        }

        $trip = $tripRequest->getTrip();

        // Get messages from tripR$tripRequest request
        $discution = $messageRepository->findBy(['tripRequest' => $tripRequest], ['createdAt' => 'ASC']);

        // Add isRead to true when opening join request
        foreach ($discution as $message) {
            if ($message->getMember() != $user) {
                $message->setIsRead(true);
            }
            // Comment next line for notif test
            $em->persist($message);
            $em->flush();
        }

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

            // MessageEventListener : send newMessageNotification
            $from = ($user == $tripRequest->getMember()) ? $tripRequest->getMember() : $tripRequest->getTrip()->getMember();
            $to = ($user == $tripRequest->getMember()) ? $tripRequest->getTrip()->getMember() : $tripRequest->getMember();

            // If $to user setting isIsNewMessage
            if (
                $to->getSetting() &&
                $to->getSetting()->isIsNewMessage()
            ) {
                // Send email Message Notification
                // TODO use mailjet templated
                $mailjet->sendNotification(
                    $to->getEmail(),
                    $to->getPseudo(),
                    'Nouveau message',
                    [
                        'title' => 'Nouveau message de ' . $from,
                        'message' => $message->getContent()
                    ]
                );
                // TODO remove when mailjet works
                $mailerService->send(
                    $to->getEmail(),
                    'Nouveau message',
                    'notification.html.twig',
                    [
                        'title' => 'Nouveau message de ' . $from,
                        'message' => $message->getContent()
                    ]
                );
            }

            return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip_request/show.html.twig', [
            'tripRequest' => $tripRequest,
            'trip' => $trip,
            'discution' => $discution,
            'form' => $form
        ]);
    }

    #[Route('/status/{id}', name: 'app_trip_request_status', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        TripRequest $tripRequest,
        EntityManagerInterface $em,
        MailerService $mailerService,
        TranslatorInterface $translator,
        MailjetService $mailjet
    ): Response {
        if (!$tripRequest) {
            return $this->redirectToRoute('app_planning_index');
        }

        /** @var User */
        $user = $this->getUser();

        // TODO: Deny if not owner of the trip
        // $this->denyAccessUnlessGranted('TRIP_OWNER', $myFriend);

        if ($this->getUser() == $tripRequest->getTrip()->getMember()) {
            $tripRequest->setStatus($request->get('status'));
            $em->flush();
            // JoinEventListener : send mail on update, status change

            $to = ($user == $tripRequest->getMember()) ? $tripRequest->getTrip()->getMember() : $tripRequest->getMember();

            // If $to user setting isIsNewMessage
            if (
                $to->getSetting() &&
                $to->getSetting()->isIsTripRequestStatusChange()
            ) {
                // Send email Message Notification
                // TODO use mailjet templated
                $mailjet->sendNotification(
                    $to->getEmail(),
                    $to->getPseudo(),
                    'Changement de status',
                    [
                        'title' => 'Changement de status pour ' . $tripRequest->getTrip()->getTitle(),
                        'message' => 'Votre demande à maintenant le status ' . $translator->trans($tripRequest->getStatus())
                    ]
                );
                // TODO remove when mailjet works
                $mailerService->send(
                    $to->getEmail(),
                    'Changement de status',
                    'notification.html.twig',
                    [
                        'title' => 'Changement de status pour ' . $tripRequest->getTrip()->getTitle(),
                        'message' => 'Votre demande à maintenant le status ' . $translator->trans($tripRequest->getStatus())
                    ]
                );
            }
        }

        return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()], Response::HTTP_SEE_OTHER);
    }

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
            $this->addFlash('warning', 'Impossible de supprimer une demande refusée');
        }

        return $this->redirectToRoute('app_trip_show', ['id' => $tripRequest->getTrip()->getId()], Response::HTTP_SEE_OTHER);
    }
}
