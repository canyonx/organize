<?php

namespace App\Controller\Api;

use App\Entity\Friend;
use App\Entity\User;
use App\Repository\FriendRepository;
use App\Repository\TripRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class FriendController extends AbstractController
{
    /**
     * Create a friend relation
     * @param User $myFriend, user to create a relation with
     */
    #[Route('/api/friend/add/{id}', name: 'app_api_friend_add')]
    public function add(
        User $myFriend,
        FriendRepository $friendRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('USER_VIEW', $myFriend);

        /** @var User */
        $user = $this->getUser();

        // if friend relation exist 
        if ($friendRepository->findOneBy(['member' => $user, 'friend' => $myFriend, 'status' => Friend::FRIEND])) return $this->json([]);

        $friend = new Friend;
        $friend->setMember($user)
            ->setFriend($myFriend)
            ->setStatus(Friend::FRIEND);
        $em->persist($friend);
        $em->flush();

        return $this->json([]);
    }

    /**
     * Create a blocked relation
     * @param User $myFriend, user to create a relation with
     */
    #[Route('/api/friend/block/{id}', name: 'app_api_friend_block')]
    public function block(
        User $myFriend,
        FriendRepository $friendRepository,
        TripRequestRepository $tripRequestRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $this->denyAccessUnlessGranted('USER_VIEW', $myFriend);

        /** @var User */
        $user = $this->getUser();

        // if blocked relation exist 
        if ($friendRepository->findOneBy(['member' => $user, 'friend' => $myFriend, 'status' => Friend::BLOCKED])) return $this->json([]);

        $friend = new Friend;
        $friend->setMember($user)
            ->setFriend($myFriend)
            ->setStatus(Friend::BLOCKED);
        $em->persist($friend);

        // delete tripRequests associated to users
        $tripRequests = $tripRequestRepository->findBy(['member' => [$user, $myFriend]]);
        foreach ($tripRequests as $tr) {
            if (
                ($tr->getMember() === $user && $tr->getTrip()->getMember() === $myFriend) ||
                ($tr->getMember() === $myFriend && $tr->getTrip()->getMember() === $user)
            ) {
                $em->remove($tr);
            }
        }

        // Looking for friend entity between myFriend and user
        $friend = $friendRepository->findOneBy(['member' => $myFriend, 'friend' => $user, 'status' => Friend::FRIEND]);
        if ($friend) $em->remove($friend);

        $em->flush();
        return $this->json([]);
    }

    /**
     * Remove a friend relation
     * @param User $user
     */
    #[Route('/api/friend/remove/{id}', name: 'app_api_friend_remove')]
    public function remove(
        User $user,
        FriendRepository $friendRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $friend = $friendRepository->findOneBy(['member' => $this->getUser(), 'friend' => $user]);
        if ($friend) $em->remove($friend);
        $em->flush();
        return $this->json([]);
    }
}
