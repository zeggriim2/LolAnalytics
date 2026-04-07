<?php

declare(strict_types=1);

namespace App\League\Presentation\Api;

use App\League\Application\Command\RefreshLeagueCommand;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[Route('/refresh', name: 'league_refresh', methods: [Request::METHOD_POST])]
final class RefreshLeagueController extends AbstractController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getPayload();
        $platformValue = strtolower($payload->getAlnum('platform'));
        $tierValue = strtolower($payload->getString('tier'));
        $queueValue = $payload->getString('queue', Queue::RANKED_SOLO->value);

        if ('' === $platformValue || '' === $tierValue) {
            return $this->json([
                'status' => 'error',
                'message' => 'Fields "platform" and "tier" are required.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid platform "%s". Valid: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))),
            ], Response::HTTP_BAD_REQUEST);
        }

        $tier = LeagueTier::tryFrom($tierValue);

        if (null === $tier) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid tier "%s". Valid: %s', $tierValue, implode(', ', array_column(LeagueTier::cases(), 'value'))),
            ], Response::HTTP_BAD_REQUEST);
        }

        $queue = Queue::tryFrom($queueValue);

        if (null === $queue) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid queue "%s".', $queueValue),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch(new RefreshLeagueCommand($platform, $tier, $queue));

            return $this->json([
                'status' => 'success',
                'message' => sprintf('%s league for platform "%s" has been refreshed.', ucfirst($tier->value), $platform->value),
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Refresh failed: %s', $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
