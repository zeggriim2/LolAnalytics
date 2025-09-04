<?php

declare(strict_types=1);

namespace App\Application\Version\Command;

final class FetchVersionsCommand
{
    public function __construct(
        private readonly bool $forceUpdate = false,
        private readonly ?string $requestId = null
    ) {
    }

    public function isForceUpdate(): bool
    {
        return $this->forceUpdate;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

}
