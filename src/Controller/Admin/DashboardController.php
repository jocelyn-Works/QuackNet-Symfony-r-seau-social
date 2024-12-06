<?php

namespace App\Controller\Admin;

use App\Entity\Duck;
use App\Entity\Quack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('QuackNet')
        ->renderSidebarMinimized()

        ->setLocales(['fr'])
        ->setLocales([
            'en' => '🇬🇧 English',
            'fr' => '🇫🇷 Français'
        ])
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour au Site', 'fa fa-undo', 'app_quack-all');
        yield MenuItem::linkToCrud('User', 'fas fa-user', Duck::class);
        yield MenuItem::linkToCrud('Quack', 'fa-solid fa-envelope', Quack::class);
    }
}
