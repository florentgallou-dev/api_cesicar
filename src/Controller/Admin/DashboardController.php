<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Report;
use App\Controller\Admin\UserCrudController;
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
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Api Cesicar');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('See API', 'fas fa-spider', '/api');
        yield MenuItem::linkToCrud('Users', 'fas fa-map-marker-alt', User::class);
        yield MenuItem::linkToCrud('Rapports', 'fas fa-land-mine-on', Report::class);
    }
}
