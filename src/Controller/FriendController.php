<?php

namespace App\Controller;

use App\Repository\FriendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/friend')]
class FriendController extends AbstractController
{
    #[Route('/', name: 'app_friend_index', methods: ['GET'])]
    public function index(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/index.html.twig', [
            'friends' => $friendRepository->findAll(),
        ]);
    }
}
