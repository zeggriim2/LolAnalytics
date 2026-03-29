<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use App\Summoner\Domain\Enum\TopLeagueTier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[Route('/import/top-league', name: 'import_top_league_summoners', methods: [Request::METHOD_POST])]
final class ImportTopLeagueSummonersController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var array{platform?: string, tier?: string, queue?: string, force?: bool} $data */
        $data = json_decode($request->getContent(), true);

        $payload = $request->getPayload();
        $platformValue = strtolower($payload->getAlnum('platform'));
        $tierValue = strtolower($payload->getString('tier'));
        $queueValue = $payload->getString('queue', Queue::RANKED_SOLO->value);
        $force = $payload->getBoolean('force');

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
                'message' => sprintf('Invalid platform "%s". Valid values: %s', $platformValue, implode(', ', array_column(Platform::cases(), 'value'))),
            ], Response::HTTP_BAD_REQUEST);
        }

        $tier = TopLeagueTier::tryFrom($tierValue);

        if (null === $tier) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid tier "%s". Valid values: %s', $tierValue, implode(', ', array_column(TopLeagueTier::cases(), 'value'))),
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
            $this->commandBus->dispatch(new ImportTopLeagueSummonersCommand($platform, $tier, $queue, $force));

            return $this->json([
                'status' => 'success',
                'message' => sprintf('%s summoners for platform "%s" have been enqueued.', ucfirst($tier->value), $platform->value),
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Import failed: %s', $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
