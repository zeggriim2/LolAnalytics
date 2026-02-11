<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Application\QueryHandler;

use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\GetSummonerByPuuidQuery;
use App\Tests\Factory\SummonerEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GetSummonerByPuuidHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $queryBus;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->queryBus = $container->get('query.bus');
    }

    public function testGetSummonerByPuuidReturnsDto(): void
    {
        // Given: a summoner in database
        SummonerEntityFactory::createOne([
            'puuid' => 'test-puuid-for-query',
            'gameName' => 'QueryTestPlayer',
            'tagLine' => 'QTP',
            'profileIconId' => 1234,
            'summonerLevel' => 250,
            'platform' => 'euw1',
        ]);

        // When: querying by puuid
        $envelope = $this->queryBus->dispatch(
            new GetSummonerByPuuidQuery('test-puuid-for-query')
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return a SummonerDto
        $this->assertInstanceOf(SummonerDto::class, $result);
        $this->assertSame('test-puuid-for-query', $result->puuid);
        $this->assertSame('QueryTestPlayer', $result->gameName);
        $this->assertSame('QTP', $result->tagLine);
        $this->assertSame(1234, $result->profileIconId);
        $this->assertSame(250, $result->summonerLevel);
        $this->assertSame('euw1', $result->platform);
    }

    public function testGetSummonerByPuuidReturnsNullWhenNotFound(): void
    {
        // Given: empty database
        // When: querying for non-existent summoner
        $envelope = $this->queryBus->dispatch(
            new GetSummonerByPuuidQuery('non-existent-puuid')
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should return null
        $this->assertNull($result);
    }

    public function testGetSummonerByPuuidReturnsDtoWithCorrectRiotId(): void
    {
        // Given: a summoner with specific Riot ID
        SummonerEntityFactory::createOne([
            'puuid' => 'riot-id-test-puuid',
            'gameName' => 'Faker',
            'tagLine' => 'KR1',
        ]);

        // When: querying by puuid
        $envelope = $this->queryBus->dispatch(
            new GetSummonerByPuuidQuery('riot-id-test-puuid')
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: DTO should have correct Riot ID format
        $this->assertInstanceOf(SummonerDto::class, $result);
        $this->assertSame('Faker#KR1', $result->riotId);
    }

    public function testGetSummonerByPuuidReturnsCorrectLastUpdatedAt(): void
    {
        // Given: a summoner with specific last updated date
        $lastUpdated = new \DateTimeImmutable('2024-06-15 14:30:00');
        SummonerEntityFactory::createOne([
            'puuid' => 'date-test-puuid',
            'lastUpdatedAt' => $lastUpdated,
        ]);

        // When: querying by puuid
        $envelope = $this->queryBus->dispatch(
            new GetSummonerByPuuidQuery('date-test-puuid')
        );
        $result = $envelope->last(HandledStamp::class)?->getResult();

        // Then: should have correct date
        $this->assertInstanceOf(SummonerDto::class, $result);
        $this->assertSame(
            $lastUpdated->format('Y-m-d H:i:s'),
            $result->lastUpdatedAt->format('Y-m-d H:i:s')
        );
    }
}
