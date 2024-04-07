<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Repository\FriendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/relations')]
class FriendController extends AbstractController
{
    #[Route('/suivis', name: 'app_friend_followed', methods: ['GET'])]
    public function friends(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/followed.html.twig', [
            'friends' => $friendRepository->findBy(['member' => $this->getUser(), 'status' => Friend::FRIEND], []),
        ]);
    }

    #[Route('/bloques', name: 'app_friend_bloqued', methods: ['GET'])]
    public function blocked(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/blocked.html.twig', [
            'friends' => $friendRepository->findBy(['member' => $this->getUser(), 'status' => Friend::BLOCKED], []),
        ]);
    }
}
