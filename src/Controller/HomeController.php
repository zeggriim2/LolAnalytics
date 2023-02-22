<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Version;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function home(ManagerRegistry $em): Response
    {
        $versions = $em->getRepository(Version::class)->findAll();

        return $this->render('home/index.html.twig', [
            'versions' => $versions
        ]);
    }
}