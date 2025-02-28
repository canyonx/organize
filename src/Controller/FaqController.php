<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FaqController extends AbstractController
{
    #[Route('/foire-aux-questions', name: 'app_faq')]
    public function index(
        FaqRepository $faqRepository,
        ActivityRepository $activityRepository,
    ): Response {
        return $this->render('faq/index.html.twig', [
            'faqs' => $faqRepository->findBy([], ['number' => 'ASC']),
            'activities' => $activityRepository->findBy([], ['name' => 'ASC']),
        ]);
    }
}
