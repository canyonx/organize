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
    #[Route('/', name: 'app_friend_relation', methods: ['GET'])]
    public function friends(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/followed.html.twig', [
            'followed' => $friendRepository->findBy(['member' => $this->getUser(), 'status' => Friend::FRIEND], []),
            'blocked' => $friendRepository->findBy(['member' => $this->getUser(), 'status' => Friend::BLOCKED], []),
        ]);
    }
}
