<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\TripRequest;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * Delete a message
     * @param Message $message
     */
    #[Route('/message/{id}/delete', name: 'app_message_delete', methods: ['GET'])]
    public function delete(
        Message $message,
        EntityManagerInterface $em
    ): Response {

        /** @var User */
        $user = $this->getUser();

        $tripRequest = $message->getTripRequest();

        if ($user != $message->getMember()) {
            throw $this->createAccessDeniedException();
        }

        // Delete message
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('app_trip_request_show', ['id' => $tripRequest->getId()], Response::HTTP_SEE_OTHER);
    }
}
