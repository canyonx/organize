<?php

namespace App\Controller\Api;

use App\Entity\Friend;
use App\Entity\User;
use App\Repository\FriendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FriendController extends AbstractController
{
    /**
     * Remove a friend relation
     * @param User $user
     */
    #[Route('/api/friend/remove/{id}', name: 'app_api_friend_remove')]
    public function remove(User $user, FriendRepository $friendRepository, EntityManagerInterface $em): Response
    {
        $friend = $friendRepository->findOneBy(['member' => $this->getUser(), 'friend' => $user]);

        $em->remove($friend);
        $em->flush();

        return $this->json([]);
    }

    /**
     * Create a friend relation
     * @param string $action, add|block
     * @param User $myFriend, user to create a relation with
     */
    #[Route('/api/friend/{action}/{id}', name: 'app_api_friend_action')]
    public function action(string $action, User $myFriend, EntityManagerInterface $em): Response
    {
        /** @var User */
        $user = $this->getUser();

        $friend = new Friend;
        $friend->setMember($user)
            ->setFriend($myFriend);


        if ($action === 'add') {
            $friend->setStatus(Friend::FRIEND);
        }

        if ($action === 'block') {
            $friend->setStatus(Friend::BLOCKED);
        }

        $em->persist($friend);
        $em->flush();

        return $this->json([]);
    }
}
