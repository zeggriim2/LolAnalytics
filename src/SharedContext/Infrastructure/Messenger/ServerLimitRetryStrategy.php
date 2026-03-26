<?php

declare(strict_types=1);

namespace App\SharedContext\Infrastructure\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Retry\RetryStrategyInterface;
use Zeggriim\RiotApiDataDragon\Exception\ServerException;
use Zeggriim\RiotApiDataDragon\Exception\ServerLimitException;

/**
 * Retry strategy that respects the Riot API rate-limit (HTTP 429).
 *
 * - On ServerLimitException (429): waits exactly `Retry-After` seconds (fallback: 60 s).
 * - On ServerException (500/503):  waits 30 s with exponential back-off (×2 each retry).
 * - Other exceptions: not retried.
 */
final class ServerLimitRetryStrategy implements RetryStrategyInterface
{
    private const MAX_RETRIES = 20;
    private const DEFAULT_RATE_LIMIT_WAIT_MS = 60_000;
    private const BASE_SERVER_ERROR_WAIT_MS = 30_000;

    public function isRetryable(Envelope $message, ?\Throwable $throwable = null): bool
    {
        if ($this->getRetryCount($message) >= self::MAX_RETRIES) {
            return false;
        }

        $cause = $this->unwrap($throwable);

        return $cause instanceof ServerLimitException
            || $cause instanceof ServerException;
    }

    public function getWaitingTime(Envelope $message, ?\Throwable $throwable = null): int
    {
        $cause = $this->unwrap($throwable);

        if ($cause instanceof ServerLimitException) {
            if (null !== $cause->retryAfter) {
                return $cause->retryAfter * 1000;
            }

            return self::DEFAULT_RATE_LIMIT_WAIT_MS;
        }

        $retryCount = $this->getRetryCount($message);

        return self::BASE_SERVER_ERROR_WAIT_MS * (2 ** $retryCount);
    }

    private function unwrap(?\Throwable $throwable): ?\Throwable
    {
        if ($throwable instanceof HandlerFailedException) {
            $nested = $throwable->getWrappedExceptions();

            return !empty($nested) ? reset($nested) : $throwable->getPrevious();
        }

        return $throwable;
    }

    private function getRetryCount(Envelope $message): int
    {
        $retryStamp = $message->last(\Symfony\Component\Messenger\Stamp\RedeliveryStamp::class);

        return null !== $retryStamp ? $retryStamp->getRetryCount() : 0;
    }
}
