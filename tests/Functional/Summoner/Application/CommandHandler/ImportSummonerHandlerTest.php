<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\SharedContext\Domain\ValueObjet\Region;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Tests\Factory\SummonerEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ImportSummonerHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $commandBus;
    private SummonerRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->repository = $container->get(SummonerRepositoryInterface::class);
    }

    public function testImportNewSummonerPersistsToDatabase(): void
    {
        // Given: mock the Riot API provider
        $puuid = 'functional-test-puuid-123';
        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'FuncTestPlayer',
            tagLine: 'EUW',
            profileIconId: 1234,
            summonerLevel: 150,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable('2024-01-15 10:00:00'),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
            ->with($puuid, Platform::EUW1, Region::EUROPE)
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerCommand($puuid, Platform::EUW1);
        $this->commandBus->dispatch($command);

        // Then: summoner should be persisted in database
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame($puuid, $summoner->puuid()->value());
        $this->assertSame('FuncTestPlayer', $summoner->riotId()->gameName());
        $this->assertSame('EUW', $summoner->riotId()->tagLine());
        $this->assertSame(1234, $summoner->profileIconId());
        $this->assertSame(150, $summoner->summonerLevel());
        $this->assertSame(Platform::EUW1, $summoner->platform());
    }

    public function testImportExistingSummonerUpdatesDatabase(): void
    {
        // Given: an existing summoner in database
        $puuid = 'existing-functional-puuid';
        SummonerEntityFactory::createOne([
            'puuid' => $puuid,
            'gameName' => 'OldName',
            'tagLine' => 'OLD',
            'profileIconId' => 1111,
            'summonerLevel' => 100,
            'platform' => 'euw1',
        ]);

        // And: mock the Riot API to return updated data
        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'NewName',
            tagLine: 'NEW',
            profileIconId: 9999,
            summonerLevel: 200,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable('2024-06-15 10:00:00'),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->method('fetchByPuuid')
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerCommand($puuid, Platform::EUW1);
        $this->commandBus->dispatch($command);

        // Then: summoner should be updated in database
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame($dto->gameName, $summoner->riotId()->gameName());
        $this->assertSame($dto->tagLine, $summoner->riotId()->tagLine());
        $this->assertSame($dto->profileIconId, $summoner->profileIconId());
        $this->assertSame($dto->summonerLevel, $summoner->summonerLevel());
    }

    public function testImportSummonerWithDifferentPlatform(): void
    {
        // Given: mock the Riot API provider for NA
        $puuid = 'na-functional-puuid';
        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'NAPlayer',
            tagLine: 'NA1',
            profileIconId: 5555,
            summonerLevel: 300,
            platform: 'na1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
            ->with($puuid, Platform::NA1, Region::AMERICAS)
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerCommand($puuid, Platform::NA1);
        $this->commandBus->dispatch($command);

        // Then: summoner should be persisted with NA platform
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame(Platform::NA1, $summoner->platform());
        $this->assertSame('NAPlayer', $summoner->riotId()->gameName());
    }

    public function testImportSummonerIsIdempotent(): void
    {
        // Given: mock the Riot API provider
        $puuid = 'idempotent-test-puuid';
        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'IdempotentPlayer',
            tagLine: 'IDP',
            profileIconId: 7777,
            summonerLevel: 175,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->method('fetchByPuuid')
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the same command twice
        $command = new ImportSummonerCommand($puuid, Platform::EUW1);
        $this->commandBus->dispatch($command);
        $this->commandBus->dispatch($command);

        // Then: should still have only one summoner
        $this->assertTrue($this->repository->exists(Puuid::fromString($puuid)));

        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));
        $this->assertSame('IdempotentPlayer', $summoner->riotId()->gameName());
    }
}
