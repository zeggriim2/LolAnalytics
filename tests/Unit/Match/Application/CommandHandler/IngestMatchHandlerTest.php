<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\CommandHandler\IngestMatchHandler;
use App\Match\Domain\Event\MatchesSavedNotification;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\Region;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\MatchApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Region as RiotRegion;

final class IngestMatchHandlerTest extends TestCase
{
    private MatchApiInterface $matchApi;
    private MatchRepositoryInterface $matchRepository;
    private MessageBusInterface $eventBus;
    private IngestMatchHandler $handler;

    protected function setUp(): void
    {
        $this->matchApi = $this->createMock(MatchApiInterface::class);
        $this->matchRepository = $this->createMock(MatchRepositoryInterface::class);
        $this->eventBus = $this->createMock(MessageBusInterface::class);

        $this->handler = new IngestMatchHandler(
            $this->matchApi,
            $this->matchRepository,
            $this->eventBus
        );
    }

    public function testIngestMatchSuccessfully(): void
    {
        $matchId = 'EUW1_1234567890';
        $region = Region::EUROPE;
        $command = new IngestMatchCommand($matchId, $region);

        // Match doesn't exist yet
        $this->matchRepository
            ->expects($this->once())
            ->method('exists')
            ->with($this->callback(function (MatchId $id) use ($matchId) {
                return (string) $id === $matchId;
            }))
            ->willReturn(false);

        // Mock Riot API response
        $riotPayload = [
            'metadata' => ['matchId' => $matchId],
            'info' => [
                'gameId' => 1234567890,
                'gameCreation' => 1705328400000,
                'gameDuration' => 1800,
                'gameMode' => 'gameMode',
                'gameType' => 'gameType',
                'mapId' => 1,
                'queueId' => 1,
                'participants' => [
                    [
                        'puuid' => 'puuid-1',
                        'summonerId' => 'summoner-1',
                        'championId' => 157,
                        'kills' => 10,
                        'deaths' => 5,
                        'assists' => 15,
                        'win' => true,
                        'item0' => 3006,
                        'item1' => 3031,
                        'item2' => 0,
                        'item3' => 0,
                        'item4' => 0,
                        'item5' => 0,
                        'item6' => 3340,
                    ],
                ],
            ],
        ];

        $riotRegion = RiotRegion::EUROPE;
        $this->matchApi
            ->expects($this->once())
            ->method('getMatch')
            ->with($matchId, $riotRegion)
            ->willReturn($riotPayload);

        // Match should be saved
        $this->matchRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Matche $match) use ($matchId) {
                    return (string) $match->id() === $matchId
                        && 1800 === $match->durationSeconds()
                        && 1 === count($match->participants());
                }),
                Region::EUROPE
            );

        // Event should be dispatched
        $this->eventBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(MatchesSavedNotification::class))
            ->willReturnCallback(fn ($event) => new Envelope($event));

        ($this->handler)($command);
    }

    public function testDoesNotIngestMatchIfAlreadyExists(): void
    {
        $matchId = 'EUW1_1234567890';
        $region = Region::EUROPE;
        $command = new IngestMatchCommand($matchId, $region);

        // Match already exists
        $this->matchRepository
            ->expects($this->once())
            ->method('exists')
            ->with($this->callback(function (MatchId $id) use ($matchId) {
                return (string) $id === $matchId;
            }))
            ->willReturn(true);

        // Should not fetch from Riot API
        $this->matchApi
            ->expects($this->never())
            ->method('getMatch');

        // Should not save
        $this->matchRepository
            ->expects($this->never())
            ->method('save');

        // Should not dispatch event
        $this->eventBus
            ->expects($this->never())
            ->method('dispatch');

        ($this->handler)($command);
    }

    public function testIngestMatchWithMultipleParticipants(): void
    {
        $matchId = 'EUW1_9876543210';
        $region = Region::AMERICAS;
        $command = new IngestMatchCommand($matchId, $region);

        $this->matchRepository
            ->method('exists')
            ->willReturn(false);

        $riotPayload = [
            'metadata' => ['matchId' => $matchId],
            'info' => [
                'gameId' => 9876543210,
                'gameCreation' => 1705328400000,
                'gameDuration' => 2400,
                'gameMode' => 'gameMode',
                'gameType' => 'gameType',
                'mapId' => 1,
                'queueId' => 1,
                'participants' => [
                    [
                        'puuid' => 'puuid-1',
                        'summonerId' => 'summoner-1',
                        'championId' => 157,
                        'kills' => 10,
                        'deaths' => 5,
                        'assists' => 15,
                        'win' => true,
                        'item0' => 0,
                        'item1' => 0,
                        'item2' => 0,
                        'item3' => 0,
                        'item4' => 0,
                        'item5' => 0,
                        'item6' => 0,
                    ],
                    [
                        'puuid' => 'puuid-2',
                        'summonerId' => 'summoner-2',
                        'championId' => 64,
                        'kills' => 3,
                        'deaths' => 10,
                        'assists' => 5,
                        'win' => false,
                        'item0' => 0,
                        'item1' => 0,
                        'item2' => 0,
                        'item3' => 0,
                        'item4' => 0,
                        'item5' => 0,
                        'item6' => 0,
                    ],
                ],
            ],
        ];

        $this->matchApi
            ->method('getMatch')
            ->willReturn($riotPayload);

        $this->matchRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Matche $match) {
                    return 2 === count($match->participants())
                        && 2400 === $match->durationSeconds();
                }),
                Region::AMERICAS
            );

        $this->eventBus
            ->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(fn ($event) => new Envelope($event));

        ($this->handler)($command);
    }
}
