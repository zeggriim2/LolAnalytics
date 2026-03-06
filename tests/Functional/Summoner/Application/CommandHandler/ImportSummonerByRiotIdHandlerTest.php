<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\SharedContext\Domain\ValueObjet\Region;
use App\Summoner\Application\Command\ImportSummonerByRiotIdCommand;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Tests\Factory\SummonerEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ImportSummonerByRiotIdHandlerTest extends KernelTestCase
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

    public function testImportNewSummonerByRiotIdPersistsToDatabase(): void
    {
        // Given: mock the Riot API provider
        $gameName = 'Faker';
        $tagLine = 'KR1';
        $puuid = 'faker-puuid-functional-123';

        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: $gameName,
            tagLine: $tagLine,
            profileIconId: 1234,
            summonerLevel: 500,
            platform: 'kr',
            lastUpdatedAt: new \DateTimeImmutable('2024-01-15 10:00:00'),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->with($gameName, $tagLine, Platform::KR, Region::ASIA)
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, Platform::KR);
        $this->commandBus->dispatch($command);

        // Then: summoner should be persisted in database
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame($puuid, $summoner->puuid()->value());
        $this->assertSame($gameName, $summoner->riotId()->gameName());
        $this->assertSame($tagLine, $summoner->riotId()->tagLine());
        $this->assertSame(500, $summoner->summonerLevel());
        $this->assertSame(Platform::KR, $summoner->platform());
    }

    public function testImportExistingSummonerByRiotIdUpdatesDatabase(): void
    {
        // Given: an existing summoner in database
        $puuid = 'existing-riot-id-puuid';
        SummonerEntityFactory::createOne([
            'puuid' => $puuid,
            'gameName' => 'OldPlayerName',
            'tagLine' => 'OLD',
            'summonerLevel' => 100,
            'platform' => 'euw1',
        ]);

        // And: mock the Riot API to return updated data
        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'NewPlayerName',
            tagLine: 'NEW',
            profileIconId: 9999,
            summonerLevel: 250,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $mockProvider = $this->createStub(RiotSummonerProviderInterface::class);
        $mockProvider
            ->method('fetchByRiotId')
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerByRiotIdCommand('NewPlayerName', 'NEW', Platform::EUW1);
        $this->commandBus->dispatch($command);

        // Then: summoner should be updated in database
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame('NewPlayerName', $summoner->riotId()->gameName());
        $this->assertSame('NEW', $summoner->riotId()->tagLine());
        $this->assertSame(250, $summoner->summonerLevel());
    }

    public function testImportSummonerByRiotIdWithSpacesInName(): void
    {
        // Given: mock the Riot API provider for a player with spaces in name
        $gameName = 'PlayerWithSpace';
        $tagLine = 'EUW';
        $puuid = 'spaces-name-puuid';

        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: $gameName,
            tagLine: $tagLine,
            profileIconId: 7777,
            summonerLevel: 175,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $mockProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->with($gameName, $tagLine, Platform::EUW1, Region::EUROPE)
            ->willReturn($dto);

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: dispatching the import command
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, Platform::EUW1);
        $this->commandBus->dispatch($command);

        // Then: summoner should be persisted correctly
        $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));

        $this->assertNotNull($summoner);
        $this->assertSame('PlayerWithSpace', $summoner->riotId()->gameName());
        $this->assertSame('PlayerWithSpace#EUW', $summoner->riotId()->fullName());
    }

    public function testImportMultipleSummonersByRiotId(): void
    {
        // Given: mock the Riot API provider for multiple summoners
        $mockProvider = $this->createStub(RiotSummonerProviderInterface::class);

        $mockProvider
            ->method('fetchByRiotId')
            ->willReturnCallback(function (string $gameName, string $tagLine) {
                return new SummonerDto(
                    puuid: 'puuid-' . strtolower($gameName),
                    gameName: $gameName,
                    tagLine: $tagLine,
                    profileIconId: 1234,
                    summonerLevel: 100,
                    platform: 'euw1',
                    lastUpdatedAt: new \DateTimeImmutable(),
                );
            });

        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockProvider);

        // When: importing multiple summoners
        $this->commandBus->dispatch(new ImportSummonerByRiotIdCommand('Player1', 'EUW', Platform::EUW1));
        $this->commandBus->dispatch(new ImportSummonerByRiotIdCommand('Player2', 'EUW', Platform::EUW1));
        $this->commandBus->dispatch(new ImportSummonerByRiotIdCommand('Player3', 'EUW', Platform::EUW1));

        // Then: all summoners should be persisted
        $this->assertTrue($this->repository->exists(Puuid::fromString('puuid-player1')));
        $this->assertTrue($this->repository->exists(Puuid::fromString('puuid-player2')));
        $this->assertTrue($this->repository->exists(Puuid::fromString('puuid-player3')));
    }
}
