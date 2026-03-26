<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportChallengerSummonersCommand;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\Enum\Queue;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ImportChallengerSummonersHandlerTest extends KernelTestCase
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

    public function testImportsChallengerSummonersFromLeague(): void
    {
        // Given: 3 puuids returned by the challenger league
        $puuids = ['challenger-puuid-1', 'challenger-puuid-2', 'challenger-puuid-3'];

        $mockLeagueProvider = $this->createMock(RiotLeagueProviderInterface::class);
        $mockLeagueProvider
            ->expects($this->once())
            ->method('getChallengerPuuids')
            ->with(Platform::EUW1, Queue::RANKED_SOLO)
            ->willReturn($puuids);

        $mockSummonerProvider = $this->createStub(RiotSummonerProviderInterface::class);
        $mockSummonerProvider
            ->method('fetchByPuuid')
            ->willReturnCallback(fn (string $puuid) => new SummonerDto(
                puuid: $puuid,
                gameName: 'Challenger',
                tagLine: 'EUW',
                profileIconId: 1,
                summonerLevel: 500,
                platform: 'euw1',
                lastUpdatedAt: new \DateTimeImmutable(),
            ));

        static::getContainer()->set(RiotLeagueProviderInterface::class, $mockLeagueProvider);
        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockSummonerProvider);

        // When
        $this->commandBus->dispatch(
            new ImportChallengerSummonersCommand(Platform::EUW1, Queue::RANKED_SOLO),
        );

        // Then: all 3 summoners are persisted
        foreach ($puuids as $puuid) {
            $summoner = $this->repository->findByPuuid(Puuid::fromString($puuid));
            $this->assertNotNull($summoner, sprintf('Summoner with puuid "%s" not found.', $puuid));
            $this->assertSame($puuid, $summoner->puuid()->value());
            $this->assertSame(Platform::EUW1, $summoner->platform());
        }
    }

    public function testImportsNothingWhenLeagueIsEmpty(): void
    {
        // Given: empty challenger league
        $mockLeagueProvider = $this->createStub(RiotLeagueProviderInterface::class);
        $mockLeagueProvider
            ->method('getChallengerPuuids')
            ->willReturn([]);

        $mockSummonerProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockSummonerProvider
            ->expects($this->never())
            ->method('fetchByPuuid');

        static::getContainer()->set(RiotLeagueProviderInterface::class, $mockLeagueProvider);
        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockSummonerProvider);

        // When
        $this->commandBus->dispatch(
            new ImportChallengerSummonersCommand(Platform::EUW1),
        );

        // Then: no summoners in DB
        $this->assertCount(0, $this->repository->findAll(0, 10));
    }
}
