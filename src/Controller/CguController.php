<?php

namespace App\Controller;

use App\Repository\CguRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CguController extends AbstractController
{
    #[Route('/cgu', name: 'app_cgu')]
    public function index(
        CguRepository $cguRepository
    ): Response {
        return $this->render('cgu/index.html.twig', [
            'cgus' => $cguRepository->findBy([], ['number' => 'ASC']),
        ]);
    }
}
