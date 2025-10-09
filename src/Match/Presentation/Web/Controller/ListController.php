<?php

declare(strict_types=1);

namespace App\Match\Presentation\Web\Controller;

use App\Match\Application\UseCase\ListMatchesUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListController extends AbstractController
{
    public function __construct(private readonly ListMatchesUseCase $listMatchesUseCase) {}

    #[Route('/matches', name: 'app_matches')]
    public function list(): Response
    {
        $matches = $this->listMatchesUseCase->execute();

        return $this->render('match/list.html.twig', [
            'matches' => $matches,
        ]);
    }
}
