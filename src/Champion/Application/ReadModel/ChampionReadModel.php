<?php

declare(strict_types=1);

namespace App\Champion\Application\ReadModel;

use App\Champion\Domain\Model\Champion;

final readonly class ChampionReadModel
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        public string $riotId,
        public string $version,
        public string $championKey,
        public string $name,
        public string $title,
        public string $partype,
        public array $tags,
        public string $imageFull,
    ) {
    }

    public static function fromDomain(Champion $champion): self
    {
        return new self(
            riotId: $champion->riotId(),
            version: $champion->version(),
            championKey: $champion->championKey(),
            name: $champion->name(),
            title: $champion->title(),
            partype: $champion->partype(),
            tags: $champion->tags(),
            imageFull: $champion->image()->full(),
        );
    }
}
