<?php

declare(strict_types=1);

namespace App\SharedContext\Presentation\Api;

use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/platforms', name: 'api_platforms_')]
final class PlatformApiController extends AbstractController
{
    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(): JsonResponse
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
