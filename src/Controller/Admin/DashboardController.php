<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Report;
use App\Entity\Travel;
use App\Entity\Inscription;
use App\Entity\Conversation;
use App\Entity\Message;
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
            ->setTitle('Cesicar BackOffice');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Visiter le site', 'fas fa-home', '/');
        yield MenuItem::linkToUrl('Voir API', 'fas fa-spider', '/api');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

        yield MenuItem::section('Voyages');
        yield MenuItem::linkToCrud('Voyages', 'fas fa-route', Travel::class);
        yield MenuItem::linkToCrud('Inscriptions', 'fas fa-receipt', Inscription::class);

        yield MenuItem::section('Conversations');
        yield MenuItem::linkToCrud('Conversations', 'fas fa-comments', Conversation::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-comment', Message::class);

        yield MenuItem::section('Rapports');
        yield MenuItem::linkToCrud('Rapports', 'fas fa-land-mine-on', Report::class);

        // yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }

    // public function configureUserMenu(UserInterface $user): UserMenu
    // {
    //     // Usually it's better to call the parent method because that gives you a
    //     // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
    //     // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
    //     return parent::configureUserMenu($user)
    //         // use the given $user object to get the user name
    //         ->setName($user->getFullName())
    //         // use this method if you don't want to display the name of the user
    //         ->displayUserName(false)

    //         // you can return an URL with the avatar image
    //         ->setAvatarUrl('https://...')
    //         ->setAvatarUrl($user->getProfileImageUrl())
    //         // use this method if you don't want to display the user image
    //         ->displayUserAvatar(false)
    //         // you can also pass an email address to use gravatar's service
    //         ->setGravatarEmail($user->getMainEmailAddress())

    //         // you can use any type of menu item, except submenus
    //         ->addMenuItems([
    //             MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
    //             MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
    //             MenuItem::section(),
    //             MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
    //         ]);
    // }
}
