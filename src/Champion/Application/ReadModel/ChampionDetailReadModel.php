<?php

declare(strict_types=1);

namespace App\Champion\Application\ReadModel;

use App\Champion\Domain\Model\Champion;

final readonly class ChampionDetailReadModel
{
    /**
     * @param string[]                                                             $tags
     * @param array<string, int|string>                                            $image
     * @param array<string, int>                                                   $info
     * @param array<string, float>                                                 $stats
     * @param array<int, array{id: string, num: int, name: string, chromas: bool}> $skins
     */
    public function __construct(
        public string $riotId,
        public string $version,
        public string $championKey,
        public string $name,
        public string $title,
        public string $blurb,
        public string $partype,
        public array $tags,
        public array $image,
        public array $info,
        public array $stats,
        public array $skins,
    ) {
    }

    public static function fromDomain(Champion $champion): self
    {
        $image = $champion->image();
        $info = $champion->info();
        $stats = $champion->stats();

        return new self(
            riotId: $champion->riotId(),
            version: $champion->version(),
            championKey: $champion->championKey(),
            name: $champion->name(),
            title: $champion->title(),
            blurb: $champion->blurb(),
            partype: $champion->partype(),
            tags: $champion->tags(),
            image: [
                'full' => $image->full(),
                'sprite' => $image->sprite(),
                'group' => $image->group(),
                'x' => $image->x(),
                'y' => $image->y(),
                'w' => $image->w(),
                'h' => $image->h(),
            ],
            info: [
                'attack' => $info->attack(),
                'defense' => $info->defense(),
                'magic' => $info->magic(),
                'difficulty' => $info->difficulty(),
            ],
            stats: [
                'hp' => $stats->hp(),
                'hpPerLevel' => $stats->hpPerLevel(),
                'mp' => $stats->mp(),
                'mpPerLevel' => $stats->mpPerLevel(),
                'moveSpeed' => $stats->moveSpeed(),
                'armor' => $stats->armor(),
                'armorPerLevel' => $stats->armorPerLevel(),
                'spellBlock' => $stats->spellBlock(),
                'spellBlockPerLevel' => $stats->spellBlockPerLevel(),
                'attackRange' => $stats->attackRange(),
                'hpRegen' => $stats->hpRegen(),
                'hpRegenPerLevel' => $stats->hpRegenPerLevel(),
                'mpRegen' => $stats->mpRegen(),
                'mpRegenPerLevel' => $stats->mpRegenPerLevel(),
                'crit' => $stats->crit(),
                'critPerLevel' => $stats->critPerLevel(),
                'attackDamage' => $stats->attackDamage(),
                'attackDamagePerLevel' => $stats->attackDamagePerLevel(),
                'attackSpeed' => $stats->attackSpeed(),
                'attackSpeedPerLevel' => $stats->attackSpeedPerLevel(),
            ],
            skins: array_map(
                static fn ($skin) => [
                    'id' => $skin->skinId(),
                    'num' => $skin->num(),
                    'name' => $skin->name(),
                    'chromas' => $skin->chromas(),
                ],
                $champion->skins(),
            ),
        );
    }
}
