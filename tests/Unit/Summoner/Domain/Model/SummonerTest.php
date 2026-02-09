<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Domain\Model;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use PHPUnit\Framework\TestCase;

final class SummonerTest extends TestCase
{
    public function testCanCreateSummoner(): void
    {
        $puuid = Puuid::fromString('iorejgiozregj');
        $riotId = RiotId::create('jarkalien', 'euw');
        $platform = Platform::EUW1;

        $summoner = Summoner::create(
            puuid: $puuid,
            riotId: $riotId,
            profileIconId: 10,
            summonerLevel: 100,
            platform: $platform,
            lastUpdatedAt: new \DateTimeImmutable()
        );

        self::assertInstanceOf(Summoner::class, $summoner);
        self::assertSame($puuid, $summoner->puuid());
        self::assertSame($riotId, $summoner->riotId());
        self::assertSame($platform, $summoner->platform());
        self::assertSame(100, $summoner->summonerLevel());
        self::assertSame(10, $summoner->profileIconId());

    }
}
