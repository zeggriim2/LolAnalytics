<?php

declare(strict_types=1);

namespace App\Tests\Unit\Champion\Application\CommandHandler;

use App\Champion\Application\Command\SyncChampionsCommand;
use App\Champion\Application\CommandHandler\SyncChampionsHandler;
use App\Champion\Application\Dto\ChampionDto;
use App\Champion\Application\Dto\ChampionImageDto;
use App\Champion\Application\Dto\ChampionInfoDto;
use App\Champion\Application\Dto\ChampionStatsDto;
use App\Champion\Application\Exception\ChampionValidationException;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SyncChampionsHandlerTest extends TestCase
{
    private RiotChampionProviderInterface $provider;
    private ChampionRepositoryInterface $repository;
    private ValidatorInterface $validator;
    private SyncChampionsHandler $handler;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(RiotChampionProviderInterface::class);
        $this->repository = $this->createMock(ChampionRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->handler = new SyncChampionsHandler(
            $this->provider,
            $this->repository,
            $this->validator,
        );
    }

    public function testSyncChampionsSuccessfully(): void
    {
        $command = new SyncChampionsCommand('15.1.1', 'fr_FR');

        $dto = $this->createChampionDto('Aatrox', '266');

        $this->provider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->with('15.1.1', 'fr_FR')
            ->willReturn([$dto]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Champion $champion) {
                return 'Aatrox' === $champion->riotId()
                    && '15.1.1' === $champion->version()
                    && '266' === $champion->championKey()
                    && 'Aatrox.png' === $champion->image()->full();
            }));

        ($this->handler)($command);
    }

    public function testSyncMultipleChampions(): void
    {
        $command = new SyncChampionsCommand('15.1.1');

        $dto1 = $this->createChampionDto('Aatrox', '266');
        $dto2 = $this->createChampionDto('Yasuo', '157');

        $this->provider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->willReturn([$dto1, $dto2]);

        $this->validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->repository
            ->expects($this->exactly(2))
            ->method('save');

        ($this->handler)($command);
    }

    public function testThrowsExceptionForInvalidChampionButSavesValidOnes(): void
    {
        $command = new SyncChampionsCommand('15.1.1');

        $validDto = $this->createChampionDto('Aatrox', '266');
        $invalidDto = $this->createChampionDto('', '0');

        $this->provider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->willReturn([$invalidDto, $validDto]);

        $violation = $this->createMock(\Symfony\Component\Validator\ConstraintViolationInterface::class);
        $violation->method('getPropertyPath')->willReturn('riotId');
        $violation->method('getMessage')->willReturn('This value should not be blank.');
        $violationList = new ConstraintViolationList([$violation]);

        $this->validator
            ->method('validate')
            ->willReturnCallback(function ($dto) use ($invalidDto, $violationList) {
                return $dto === $invalidDto ? $violationList : new ConstraintViolationList();
            });

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(fn (Champion $c) => 'Aatrox' === $c->riotId()));

        $this->expectException(ChampionValidationException::class);

        ($this->handler)($command);
    }

    public function testSyncWithEmptyChampionList(): void
    {
        $command = new SyncChampionsCommand('15.1.1');

        $this->provider
            ->expects($this->once())
            ->method('fetchAllChampions')
            ->willReturn([]);

        $this->repository
            ->expects($this->never())
            ->method('save');

        ($this->handler)($command);
    }

    public function testSyncUsesDefaultLocale(): void
    {
        $command = new SyncChampionsCommand('15.1.1');

        self::assertSame('fr_FR', $command->locale);
    }

    private function createChampionDto(string $riotId, string $key): ChampionDto
    {
        return new ChampionDto(
            riotId: $riotId,
            version: '15.1.1',
            championKey: $key,
            name: $riotId,
            title: 'Title',
            blurb: 'Blurb',
            partype: 'Mana',
            tags: ['Fighter'],
            image: new ChampionImageDto(
                full: $riotId . '.png',
                sprite: 'champion0.png',
                group: 'champion',
                x: 0,
                y: 0,
                w: 48,
                h: 48,
            ),
            info: new ChampionInfoDto(
                attack: 5,
                defense: 5,
                magic: 5,
                difficulty: 5
            ),
            stats: new ChampionStatsDto(
                hp: 580.0,
                hpPerLevel: 90.0,
                mp: 350.0,
                mpPerLevel: 32.0,
                moveSpeed: 345.0,
                armor: 38.0,
                armorPerLevel: 3.25,
                spellBlock: 32.0,
                spellBlockPerLevel: 1.25,
                attackRange: 175.0,
                hpRegen: 3.0,
                hpRegenPerLevel: 1.0,
                mpRegen: 8.0,
                mpRegenPerLevel: 0.8,
                crit: 0.0,
                critPerLevel: 0.0,
                attackDamage: 60.0,
                attackDamagePerLevel: 5.0,
                attackSpeed: 0.651,
                attackSpeedPerLevel: 2.5,
            ),
        );
    }
}
