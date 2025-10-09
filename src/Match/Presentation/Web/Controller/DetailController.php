<?php

declare(strict_types=1);

namespace App\Match\Presentation\Web\Controller;

use App\Match\Application\UseCase\GetMatchDetailsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    public function __construct(private readonly GetMatchDetailsUseCase $getMatchUseCase)
    {
    }

    #[Route('/matches/{id}', name: 'app_match_details')]
    public function details(string $id): Response
    {
        $res = $this->getMatchUseCase->execute($id);

        return $this->render('match/details.html.twig', [
            'match' => $res,
        ]);
    }
}
