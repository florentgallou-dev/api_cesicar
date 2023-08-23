<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Report;
use App\Entity\Travel;
use App\Entity\Message;
use App\Entity\Inscription;
use App\Entity\Conversation;
use App\Controller\Admin\ReportCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ReportCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cesicar BackOffice');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Visiter le site', 'fas fa-home', 'http://127.0.0.1:3000/');
        yield MenuItem::linkToUrl('Voir API', 'fas fa-spider', '/api')
                        ->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Utilisateurs')
                        ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)
                        ->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Voyages');
        yield MenuItem::linkToCrud('Voyages', 'fas fa-route', Travel::class);

        yield MenuItem::section('Conversations');
        yield MenuItem::linkToCrud('Conversations', 'fas fa-comments', Conversation::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-comment', Message::class);

        yield MenuItem::section('Rapports');
        yield MenuItem::linkToCrud('Rapports', 'fas fa-land-mine-on', Report::class);

        yield MenuItem::section('---');
        yield MenuItem::linkToLogout('Logout', 'fa fa-door-open');
    }

}
