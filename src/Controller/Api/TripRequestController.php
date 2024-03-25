<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\TripRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TripRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TripRequestController extends AbstractController
{
    /**
     * TODO :unused
     *
     * Change status of a TripRequest
     * @param TripRequest $tripRequest
     */
    #[Route('/api/trip/request/status', name: 'app_api_trip_request_status', methods: ['POST'])]
    public function accept(
        Request $request,
        EntityManagerInterface $em,
        TripRequestRepository $tripRequestRepository
    ): JsonResponse {
        $postData = json_decode($request->getContent());
        dump($postData);
        $id = $postData->id;
        $status = $postData->status;

        $tripRequest = $tripRequestRepository->find($id);

        if (!$tripRequest) {
            return $this->redirectToRoute('app_planning_index');
        }

        /** @var User */
        $user = $this->getUser();

        // TODO: Deny if not owner of the trip
        // $this->denyAccessUnlessGranted('TRIP_OWNER', $myFriend);


        // Edit Trip repository and switch status to accepted
        if ($user == $tripRequest->getTrip()->getMember()) {
            $tripRequest->setStatus($status);
            $em->flush();
            // JoinEventListener : send mail on update, status change
        }
        return $this->json([]);
    }
}
