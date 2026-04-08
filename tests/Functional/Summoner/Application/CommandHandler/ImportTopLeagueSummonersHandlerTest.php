<?php

declare(strict_types=1);

namespace App\Tests\Functional\Summoner\Application\CommandHandler;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\Enum\Queue;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ImportTopLeagueSummonersHandlerTest extends KernelTestCase
{
    use ResetDatabase;

    private MessageBusInterface $commandBus;
    private SummonerRepositoryInterface $summonerRepository;
    private LeagueRepositoryInterface $leagueRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->commandBus = $container->get('command.bus');
        $this->summonerRepository = $container->get(SummonerRepositoryInterface::class);
        $this->leagueRepository = $container->get(LeagueRepositoryInterface::class);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('tierProvider')]
    public function testImportsSummonersFromStoredLeague(LeagueTier $tier): void
    {
        // Given: league pre-populated in DB
        $puuids = ['top-puuid-1', 'top-puuid-2', 'top-puuid-3'];
        $this->leagueRepository->save(League::create(
            $tier,
            Queue::RANKED_SOLO,
            Platform::EUW1,
            0,
            array_map(fn (string $p): LeagueEntry => LeagueEntry::create($p, 1000, 50, 30, null, false, false, false), $puuids),
            new \DateTimeImmutable(),
        ));

        $mockSummonerProvider = $this->createStub(RiotSummonerProviderInterface::class);
        $mockSummonerProvider
            ->method('fetchByPuuid')
            ->willReturnCallback(fn (string $puuid) => new SummonerDto(
                puuid: $puuid,
                gameName: 'Player',
                tagLine: 'EUW',
                profileIconId: 1,
                summonerLevel: 500,
                platform: 'euw1',
                lastUpdatedAt: new \DateTimeImmutable(),
            ));
        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockSummonerProvider);

        // When
        $this->commandBus->dispatch(
            new ImportTopLeagueSummonersCommand(Platform::EUW1, $tier, Queue::RANKED_SOLO),
        );

        // Then: all 3 summoners are persisted
        foreach ($puuids as $puuid) {
            $summoner = $this->summonerRepository->findByPuuid(Puuid::fromString($puuid));
            $this->assertNotNull($summoner, sprintf('Summoner with puuid "%s" not found.', $puuid));
            $this->assertSame($puuid, $summoner->puuid()->value());
            $this->assertSame(Platform::EUW1, $summoner->platform());
        }
    }

    public function testImportsNothingWhenLeagueIsEmpty(): void
    {
        // Given: no league in DB
        $mockSummonerProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $mockSummonerProvider->expects($this->never())->method('fetchByPuuid');
        static::getContainer()->set(RiotSummonerProviderInterface::class, $mockSummonerProvider);

        // When
        $this->commandBus->dispatch(new ImportTopLeagueSummonersCommand(Platform::EUW1));

        // Then
        $this->assertCount(0, $this->summonerRepository->findAll(0, 10));
    }

    /**
     * @return iterable<string, array{LeagueTier}>
     */
    public static function tierProvider(): iterable
    {
        yield 'challenger' => [LeagueTier::CHALLENGER];
        yield 'grandmaster' => [LeagueTier::GRANDMASTER];
        yield 'master' => [LeagueTier::MASTER];
    }
}
