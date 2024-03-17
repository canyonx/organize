<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Activity;
use App\Entity\Feature;
use App\Entity\Homepage;
use App\Entity\Legal;
use App\Entity\Message;
use App\Entity\Signal;
use App\Repository\TripRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private TripRepository $tripRepository
    ) {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Organize');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Signalments');
        yield MenuItem::linkToCrud('Signalments', 'fas fa-triangle-exclamation', Signal::class);

        yield MenuItem::section('Trips');
        // Événements passés
        yield MenuItem::linkToCrud('Past', 'fas fa-chevron-left', Trip::class)
            ->setQueryParameter('filters[dateAt][comparison]', '<')
            ->setQueryParameter('filters[dateAt][value]', (new \DateTimeImmutable('today'))->format('Y-m-d\T00:00'))
            ->setBadge(count($this->tripRepository->findByPeriod("<")));

        // Événements a venir
        yield MenuItem::linkToCrud('Future', 'fas fa-chevron-right', Trip::class)
            ->setQueryParameter('filters[dateAt][comparison]', '>')
            ->setQueryParameter('filters[dateAt][value]', (new \DateTimeImmutable('today'))->format('Y-m-d\T00:00'))
            ->setBadge(count($this->tripRepository->findByPeriod(">=")));

        yield MenuItem::section('Messages');
        yield MenuItem::linkToCrud('Messages', 'fas fa-comment', Message::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);

        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Activities', 'fas fa-bicycle', Activity::class);
        yield MenuItem::linkToCrud('Homepage', 'fas fa-house', Homepage::class)
            ->setQueryParameter('crudAction', 'detail')
            ->setQueryParameter('entityId', '1');
        yield MenuItem::linkToCrud('Features', 'fas fa-list', Feature::class);
        yield MenuItem::linkToCrud('Legal', 'fas fa-gavel', Legal::class);
    }
}
