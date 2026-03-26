<?php

declare(strict_types=1);

namespace App\Tests\Unit\SharedContext\Infrastructure\Messenger;

use App\SharedContext\Infrastructure\Messenger\ServerLimitRetryStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Zeggriim\RiotApiDataDragon\Exception\ServerException;
use Zeggriim\RiotApiDataDragon\Exception\ServerLimitException;

final class ServerLimitRetryStrategyTest extends TestCase
{
    private ServerLimitRetryStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ServerLimitRetryStrategy();
    }

    // ─── isRetryable ─────────────────────────────────────────────────────────

    public function testIsRetryableForServerLimitException(): void
    {
        $envelope = new Envelope(new \stdClass());

        $this->assertTrue(
            $this->strategy->isRetryable($envelope, new ServerLimitException()),
        );
    }

    public function testIsRetryableForServerException(): void
    {
        $envelope = new Envelope(new \stdClass());

        $this->assertTrue(
            $this->strategy->isRetryable($envelope, new ServerException()),
        );
    }

    public function testIsNotRetryableForOtherExceptions(): void
    {
        $envelope = new Envelope(new \stdClass());

        $this->assertFalse(
            $this->strategy->isRetryable($envelope, new \RuntimeException('some error')),
        );
    }

    public function testIsNotRetryableWhenNull(): void
    {
        $envelope = new Envelope(new \stdClass());

        $this->assertFalse($this->strategy->isRetryable($envelope, null));
    }

    public function testIsNotRetryableWhenMaxRetriesExceeded(): void
    {
        $envelope = new Envelope(new \stdClass(), [new RedeliveryStamp(20)]);

        $this->assertFalse(
            $this->strategy->isRetryable($envelope, new ServerLimitException()),
        );
    }

    public function testIsRetryableAtLastAllowedRetry(): void
    {
        $envelope = new Envelope(new \stdClass(), [new RedeliveryStamp(19)]);

        $this->assertTrue(
            $this->strategy->isRetryable($envelope, new ServerLimitException()),
        );
    }

    public function testUnwrapsHandlerFailedException(): void
    {
        $envelope = new Envelope(new \stdClass());
        $cause = new ServerLimitException(retryAfter: 30);
        $wrapped = new HandlerFailedException($envelope, [$cause]);

        $this->assertTrue($this->strategy->isRetryable($envelope, $wrapped));
    }

    // ─── getWaitingTime ───────────────────────────────────────────────────────

    public function testWaitingTimeUsesRetryAfterHeader(): void
    {
        $envelope = new Envelope(new \stdClass());
        $cause = new ServerLimitException(retryAfter: 45);

        $this->assertSame(45_000, $this->strategy->getWaitingTime($envelope, $cause));
    }

    public function testWaitingTimeFallsBackTo60sWhenRetryAfterAbsent(): void
    {
        $envelope = new Envelope(new \stdClass());
        $cause = new ServerLimitException(retryAfter: null);

        $this->assertSame(60_000, $this->strategy->getWaitingTime($envelope, $cause));
    }

    public function testWaitingTimeIsExponentialForServerError(): void
    {
        // First retry (count = 0): 30_000 * 2^0 = 30_000
        $envelopeFirst = new Envelope(new \stdClass());
        $this->assertSame(30_000, $this->strategy->getWaitingTime($envelopeFirst, new ServerException()));

        // Second retry (count = 1): 30_000 * 2^1 = 60_000
        $envelopeSecond = new Envelope(new \stdClass(), [new RedeliveryStamp(1)]);
        $this->assertSame(60_000, $this->strategy->getWaitingTime($envelopeSecond, new ServerException()));

        // Third retry (count = 2): 30_000 * 2^2 = 120_000
        $envelopeThird = new Envelope(new \stdClass(), [new RedeliveryStamp(2)]);
        $this->assertSame(120_000, $this->strategy->getWaitingTime($envelopeThird, new ServerException()));
    }

    public function testWaitingTimeExtractsRetryAfterFromWrappedException(): void
    {
        $envelope = new Envelope(new \stdClass());
        $cause = new ServerLimitException(retryAfter: 120);
        $wrapped = new HandlerFailedException($envelope, [$cause]);

        $this->assertSame(120_000, $this->strategy->getWaitingTime($envelope, $wrapped));
    }
}
