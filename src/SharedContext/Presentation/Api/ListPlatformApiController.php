<?php

declare(strict_types=1);

namespace App\SharedContext\Presentation\Api;

use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/platforms', name: 'api_platforms_list')]
final class ListPlatformApiController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        $platforms = array_map(
            static fn (Platform $platform) => [
                'value' => $platform->value,
                'label' => strtoupper($platform->name),
            ],
            Platform::cases()
        );

        return $this->json(['data' => $platforms]);
    }
}
