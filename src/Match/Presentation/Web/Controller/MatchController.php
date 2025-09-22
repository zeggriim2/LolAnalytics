<?php

declare(strict_types=1);

namespace App\Match\Presentation\Web\Controller;

use App\Match\Application\Command\IngestMatchCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

final class MatchController
{
    public function __construct(private readonly MessageBusInterface $bus) {}

    public function ingest(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent() ?: '{}', true);
        if (empty($data['matchId']) || empty($data['region'])) {
            return new JsonResponse(['error' => 'matchId and region required'], 400);
        }

        $cmd = new IngestMatchCommand($data['matchId'], $data['region']);
        $this->bus->dispatch($cmd);

        return new JsonResponse(['status' => 'accepted'], 202);
    }
}
