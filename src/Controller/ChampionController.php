<?php

namespace App\Controller;

use App\Entity\Champion;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChampionController extends AbstractController
{
    #[Route('/champion/{name}', name: 'app_champion')]
    public function index(
        string $name,
        ManagerRegistry $em
    ): Response
    {
        $champion = $em->getRepository(Champion::class)->findOneBy(['idLol' => ucfirst(strtolower($name))]);
        return $this->render('champion/index.html.twig', [
            'champion' => $champion
        ]);
    }
}
