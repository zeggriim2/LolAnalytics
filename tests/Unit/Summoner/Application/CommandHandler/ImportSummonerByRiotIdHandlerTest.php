<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerByRiotIdCommand;
use App\Summoner\Application\CommandHandler\ImportSummonerByRiotIdHandler;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Exception\SummonerValidationException;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ImportSummonerByRiotIdHandlerTest extends TestCase
{
    private RiotSummonerProviderInterface $summonerProvider;
    private SummonerRepositoryInterface $summonerRepository;
    private ValidatorInterface $validator;
    private ImportSummonerByRiotIdHandler $handler;

    protected function setUp(): void
    {
        $this->summonerProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $this->summonerRepository = $this->createMock(SummonerRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->handler = new ImportSummonerByRiotIdHandler(
            $this->summonerProvider,
            $this->summonerRepository,
            $this->validator,
        );
    }

    public function testImportNewSummonerByRiotId(): void
    {
        // Given
        $gameName = 'Faker';
        $tagLine = 'KR1';
        $platform = Platform::KR;
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, $platform);

        $dto = new SummonerDto(
            puuid: 'faker-puuid-123',
            gameName: $gameName,
            tagLine: $tagLine,
            profileIconId: 1234,
            summonerLevel: 500,
            platform: 'kr',
            lastUpdatedAt: new \DateTimeImmutable('2024-01-15 10:00:00'),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->with($gameName, $tagLine, $platform, $platform->toRegion())
            ->willReturn($dto);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->summonerRepository
            ->expects($this->once())
            ->method('findByPuuid')
            ->willReturn(null);

        $this->summonerRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Summoner $summoner) use ($gameName, $tagLine) {
                return 'faker-puuid-123' === $summoner->puuid()->value()
                    && $summoner->riotId()->gameName() === $gameName
                    && $summoner->riotId()->tagLine() === $tagLine
                    && Platform::KR === $summoner->platform();
            }));

        // When
        ($this->handler)($command);
    }

    public function testImportExistingSummonerByRiotIdUpdatesIt(): void
    {
        // Given
        $gameName = 'UpdatedPlayer';
        $tagLine = 'EUW';
        $platform = Platform::EUW1;
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, $platform);

        $dto = new SummonerDto(
            puuid: 'existing-puuid-456',
            gameName: $gameName,
            tagLine: $tagLine,
            profileIconId: 9999,
            summonerLevel: 250,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable('2024-06-15 10:00:00'),
        );

        $existingSummoner = Summoner::create(
            Puuid::fromString('existing-puuid-456'),
            RiotId::create('OldPlayer', 'OLD'),
            1111,
            100,
            Platform::EUW1,
            new \DateTimeImmutable('2024-01-01 10:00:00'),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->willReturn($dto);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->summonerRepository
            ->expects($this->once())
            ->method('findByPuuid')
            ->willReturn($existingSummoner);

        $this->summonerRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Summoner $summoner) use ($gameName, $tagLine) {
                return $summoner->riotId()->gameName() === $gameName
                    && $summoner->riotId()->tagLine() === $tagLine
                    && 250 === $summoner->summonerLevel();
            }));

        // When
        ($this->handler)($command);
    }

    public function testImportSummonerByRiotIdWithSpecialCharacters(): void
    {
        // Given
        $gameName = 'Player With Spaces';
        $tagLine = 'TAG';
        $platform = Platform::NA1;
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, $platform);

        $dto = new SummonerDto(
            puuid: 'special-puuid-789',
            gameName: $gameName,
            tagLine: $tagLine,
            profileIconId: 7777,
            summonerLevel: 175,
            platform: 'na1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->with($gameName, $tagLine, $platform, $platform->toRegion())
            ->willReturn($dto);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->summonerRepository
            ->expects($this->once())
            ->method('findByPuuid')
            ->willReturn(null);

        $this->summonerRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Summoner $summoner) use ($gameName) {
                return $summoner->riotId()->gameName() === $gameName;
            }));

        // When
        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenDtoValidationFails(): void
    {
        $gameName = 'Faker';
        $tagLine = 'KR1';
        $platform = Platform::KR;
        $command = new ImportSummonerByRiotIdCommand($gameName, $tagLine, $platform);

        $dto = new SummonerDto(
            puuid: 'faker-puuid-123',
            gameName: '',
            tagLine: '',
            profileIconId: 0,
            summonerLevel: 1,
            platform: 'kr',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByRiotId')
            ->willReturn($dto);

        $violation = $this->createStub(ConstraintViolationInterface::class);
        $violation->method('getPropertyPath')->willReturn('gameName');
        $violation->method('getMessage')->willReturn('This value should not be blank.');

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList([$violation]));

        $this->summonerRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(SummonerValidationException::class);

        ($this->handler)($command);
    }
}
