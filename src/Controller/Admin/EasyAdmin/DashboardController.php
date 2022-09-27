<?php

namespace App\Controller\Admin\EasyAdmin;

use App\Entity\Champion;
use App\Entity\Competition;
use App\Entity\Equipe;
use App\Entity\InfoChampion;
use App\Entity\Language;
use App\Entity\Map;
use App\Entity\StatChampion;
use App\Entity\User;
use App\Entity\Version;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PhpParser\Node\Expr\Yield_;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Lol Analytics');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);

        yield MenuItem::section("General");
        yield MenuItem::linkToCrud("Version", "fas fa-list", Version::class);
        yield MenuItem::linkToCrud("Map", "far fa-map", Map::class);
        yield MenuItem::linkToCrud('Language', 'fas fa-language', Language::class);

        yield MenuItem::section("Champion");
        yield MenuItem::linkToCrud('Champion', 'fas fa-list-ul', Champion::class);
        yield MenuItem::linkToCrud('Info Champion', 'fas fa-list-ul', InfoChampion::class);
        yield MenuItem::linkToCrud('Stat Champion', 'fas fa-list-ul', StatChampion::class);

        yield MenuItem::section('Competition');
        yield MenuItem::linkToCrud('Equipe', 'fas fa-list-ul', Equipe::class);
        yield MenuItem::linkToCrud('Compétition', 'fas fa-list-ul', Competition::class);
    }
}
