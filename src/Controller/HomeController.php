<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\FeatureRepository;
use App\Repository\HomepageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ActivityRepository $activityRepository,
        HomepageRepository $homepageRepository,
        FeatureRepository $featureRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'home' => $homepageRepository->findOneBy([], []),
            'activities' => $activityRepository->findBy([], ['name' => 'ASC']),
            'features' => $featureRepository->findBy([], ['id' => 'ASC'])
        ]);
    }
}
