<?php

declare(strict_types=1);

namespace App\Ui\Controller\Version;

use App\Application\Version\Query\GetVersionsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class VersionListController
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer
    ) {}
    #[Route('/versions', methods: [Request::METHOD_GET])]
    public function __invoke(): JsonResponse
    {
        $versions = $this->handle(new GetVersionsQuery());

        $versions = $this->serializer->serialize($versions, 'json');

        return new JsonResponse($versions, json: true);
    }
}
