<?php

declare(strict_types=1);

namespace App\League\Presentation\Api;

use App\League\Application\Query\GetLeagueEntriesQuery;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\ValueObjet\Platform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[Route('', name: 'league_entries_list', methods: [Request::METHOD_GET])]
final class GetLeagueEntriesController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $platformValue = strtolower($request->query->getAlnum('platform', 'euw1'));
        $tierValue = strtolower($request->query->getString('tier', LeagueTier::CHALLENGER->value));
        $queueValue = $request->query->getString('queue', Queue::RANKED_SOLO->value);
        $page = max(1, $request->query->getInt('page', 1));
        $limit = min(100, max(1, $request->query->getInt('limit', 50)));

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            return $this->json(['message' => sprintf('Invalid platform "%s".', $platformValue)], Response::HTTP_BAD_REQUEST);
        }

        $tier = LeagueTier::tryFrom($tierValue);

        if (null === $tier) {
            return $this->json(['message' => sprintf('Invalid tier "%s".', $tierValue)], Response::HTTP_BAD_REQUEST);
        }

        $queue = Queue::tryFrom($queueValue);

        if (null === $queue) {
            return $this->json(['message' => sprintf('Invalid queue "%s".', $queueValue)], Response::HTTP_BAD_REQUEST);
        }

        /** @var PaginatedResult<\App\League\Application\Dto\LeagueEntryListDto> $result */
        $result = $this->queryBus->handle(new GetLeagueEntriesQuery($platform, $tier, $queue, $page, $limit));

        return $this->json([
            'data' => $result->items,
            'meta' => [
                'total' => $result->total,
                'page' => $result->page,
                'limit' => $result->limit,
                'totalPages' => $result->totalPages,
            ],
        ]);
    }
}
