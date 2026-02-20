<?php

declare(strict_types=1);

namespace App\Champion\Application\CommandHandler;

use App\Champion\Application\Command\SyncChampionCommand;
use App\Champion\Application\Dto\ChampionDto;
use App\Champion\Application\Exception\ChampionValidationException;
use App\Champion\Application\Port\RiotChampionProviderInterface;
use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Model\ChampionImage;
use App\Champion\Domain\Model\ChampionInfo;
use App\Champion\Domain\Model\ChampionSkin;
use App\Champion\Domain\Model\ChampionStats;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler('command.bus')]
final class SyncChampionHandler
{
    public function __construct(
        private readonly RiotChampionProviderInterface $provider,
        private readonly ChampionRepositoryInterface $repository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(SyncChampionCommand $command): void
    {
        $dto = $this->provider->fetchChampion(
            $command->champion,
            $command->version,
            $command->locale
        );

        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            throw ChampionValidationException::forSingle($dto->riotId, $violations);
        }

        $champion = $this->createDomainModel($dto);
        $this->repository->save($champion);
    }

    private function createDomainModel(ChampionDto $dto): Champion
    {
        return Champion::create(
            riotId: $dto->riotId,
            version: $dto->version,
            championKey: $dto->championKey,
            name: $dto->name,
            title: $dto->title,
            blurb: $dto->blurb,
            partype: $dto->partype,
            tags: $dto->tags,
            image: new ChampionImage(
                full: $dto->image->full,
                sprite: $dto->image->sprite,
                group: $dto->image->group,
                x: $dto->image->x,
                y: $dto->image->y,
                w: $dto->image->w,
                h: $dto->image->h,
            ),
            info: new ChampionInfo(
                attack: $dto->info->attack,
                defense: $dto->info->defense,
                magic: $dto->info->magic,
                difficulty: $dto->info->difficulty,
            ),
            stats: new ChampionStats(
                hp: $dto->stats->hp,
                hpPerLevel: $dto->stats->hpPerLevel,
                mp: $dto->stats->mp,
                mpPerLevel: $dto->stats->mpPerLevel,
                moveSpeed: $dto->stats->moveSpeed,
                armor: $dto->stats->armor,
                armorPerLevel: $dto->stats->armorPerLevel,
                spellBlock: $dto->stats->spellBlock,
                spellBlockPerLevel: $dto->stats->spellBlockPerLevel,
                attackRange: $dto->stats->attackRange,
                hpRegen: $dto->stats->hpRegen,
                hpRegenPerLevel: $dto->stats->hpRegenPerLevel,
                mpRegen: $dto->stats->mpRegen,
                mpRegenPerLevel: $dto->stats->mpRegenPerLevel,
                crit: $dto->stats->crit,
                critPerLevel: $dto->stats->critPerLevel,
                attackDamage: $dto->stats->attackDamage,
                attackDamagePerLevel: $dto->stats->attackDamagePerLevel,
                attackSpeed: $dto->stats->attackSpeed,
                attackSpeedPerLevel: $dto->stats->attackSpeedPerLevel,
            ),
            skins: array_map(
                static fn ($skin) => new ChampionSkin(
                    skinId: $skin->id,
                    num: $skin->num,
                    name: $skin->name,
                    chromas: $skin->chromas,
                ),
                $dto->skins,
            ),
        );
    }
}
