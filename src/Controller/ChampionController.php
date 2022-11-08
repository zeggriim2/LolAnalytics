<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ChampionRepository;
use App\Repository\VersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/champion', name: "champion_")]
class ChampionController extends AbstractController
{

    #[Route('/all', name: "all")]
    public function all(
        ChampionRepository $championRepository,
        VersionRepository $versionRepository
    ): Response
    {
        $champions = $championRepository->findBy(['version' => $versionRepository->findOneBy(['lastVersion'=> true])]);
        return $this->render('champion/all.html.twig', [
            'champions' => $champions
        ]);
    }
}