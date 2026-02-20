<?php

declare(strict_types=1);

namespace App\Tests\Unit\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\CommandHandler\ImportSummonerHandler;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Exception\SummonerValidationException;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ImportSummonerHandlerTest extends TestCase
{
    private RiotSummonerProviderInterface $summonerProvider;
    private SummonerRepositoryInterface $summonerRepository;
    private ValidatorInterface $validator;
    private ImportSummonerHandler $handler;

    protected function setUp(): void
    {
        $this->summonerProvider = $this->createMock(RiotSummonerProviderInterface::class);
        $this->summonerRepository = $this->createMock(SummonerRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->handler = new ImportSummonerHandler(
            $this->summonerProvider,
            $this->summonerRepository,
            $this->validator,
        );
    }

    public function testImportNewSummoner(): void
    {
        // Given
        $puuid = 'test-puuid-123';
        $platform = Platform::EUW1;
        $command = new ImportSummonerCommand($puuid, $platform);

        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'TestPlayer',
            tagLine: 'EUW',
            profileIconId: 1234,
            summonerLevel: 150,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable('2024-01-15 10:00:00'),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
            ->with($puuid, $platform, $platform->toRegion())
            ->willReturn($dto);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->summonerRepository
            ->expects($this->once())
            ->method('findByPuuid')
            ->with($this->callback(fn (Puuid $p) => $p->value() === $puuid))
            ->willReturn(null);

        $this->summonerRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Summoner $summoner) use ($puuid) {
                return $puuid === $summoner->puuid()->value()
                    && 'TestPlayer' === $summoner->riotId()->gameName()
                    && 'EUW' === $summoner->riotId()->tagLine()
                    && 1234 === $summoner->profileIconId()
                    && 150 === $summoner->summonerLevel();
            }));

        // When
        ($this->handler)($command);
    }

    public function testImportExistingSummonerUpdatesIt(): void
    {
        // Given
        $puuid = 'existing-puuid-123';
        $platform = Platform::EUW1;
        $command = new ImportSummonerCommand($puuid, $platform);

        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'UpdatedName',
            tagLine: 'NEW',
            profileIconId: 9999,
            summonerLevel: 200,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable('2024-06-15 10:00:00'),
        );

        $existingSummoner = Summoner::create(
            Puuid::fromString($puuid),
            \App\Summoner\Domain\ValueObject\RiotId::create('OldName', 'OLD'),
            1111,
            100,
            Platform::EUW1,
            new \DateTimeImmutable('2024-01-01 10:00:00'),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
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
            ->with($this->callback(function (Summoner $summoner) {
                return 'UpdatedName' === $summoner->riotId()->gameName()
                    && 'NEW' === $summoner->riotId()->tagLine()
                    && 200 === $summoner->summonerLevel();
            }));

        // When
        ($this->handler)($command);
    }

    public function testImportSummonerWithDifferentPlatforms(): void
    {
        // Given
        $puuid = 'na-puuid-123';
        $platform = Platform::NA1;
        $command = new ImportSummonerCommand($puuid, $platform);

        $dto = new SummonerDto(
            puuid: $puuid,
            gameName: 'NAPlayer',
            tagLine: 'NA1',
            profileIconId: 5555,
            summonerLevel: 300,
            platform: 'na1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
            ->with($puuid, Platform::NA1, $platform->toRegion())
            ->willReturn($dto);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->summonerRepository
            ->method('findByPuuid')
            ->willReturn(null);

        $this->summonerRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Summoner $summoner) {
                return Platform::NA1 === $summoner->platform();
            }));

        // When
        ($this->handler)($command);
    }

    public function testThrowsExceptionWhenDtoValidationFails(): void
    {
        $puuid = 'invalid-puuid';
        $platform = Platform::EUW1;
        $command = new ImportSummonerCommand($puuid, $platform);

        $dto = new SummonerDto(
            puuid: '',
            gameName: '',
            tagLine: '',
            profileIconId: 0,
            summonerLevel: 1,
            platform: 'euw1',
            lastUpdatedAt: new \DateTimeImmutable(),
        );

        $this->summonerProvider
            ->expects($this->once())
            ->method('fetchByPuuid')
            ->willReturn($dto);

        $violation = $this->createMock(ConstraintViolationInterface::class);
        $violation->method('getPropertyPath')->willReturn('puuid');
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
