<?php

declare(strict_types=1);

namespace App\Champion\Domain\Model;

final class Champion
{
    /**
     * @param string[]       $tags
     * @param ChampionSkin[] $skins
     */
    private function __construct(
        private readonly string $riotId,
        private readonly string $version,
        private readonly string $championKey,
        private readonly string $name,
        private readonly string $title,
        private readonly string $blurb,
        private readonly string $partype,
        private readonly array $tags,
        private readonly ChampionImage $image,
        private readonly ChampionInfo $info,
        private readonly ChampionStats $stats,
        private readonly array $skins,
    ) {
    }

    /**
     * @param string[]       $tags
     * @param ChampionSkin[] $skins
     */
    public static function create(
        string $riotId,
        string $version,
        string $championKey,
        string $name,
        string $title,
        string $blurb,
        string $partype,
        array $tags,
        ChampionImage $image,
        ChampionInfo $info,
        ChampionStats $stats,
        array $skins = [],
    ): self {
        if ('' === $riotId) {
            throw new \InvalidArgumentException('riotId must not be empty');
        }

        if ('' === $version) {
            throw new \InvalidArgumentException('version must not be empty');
        }

        if ('' === $championKey) {
            throw new \InvalidArgumentException('championKey must not be empty');
        }

        if ('' === $name) {
            throw new \InvalidArgumentException('name must not be empty');
        }

        return new self(
            $riotId,
            $version,
            $championKey,
            $name,
            $title,
            $blurb,
            $partype,
            $tags,
            $image,
            $info,
            $stats,
            $skins,
        );
    }

    public function riotId(): string
    {
        return $this->riotId;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function championKey(): string
    {
        return $this->championKey;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function blurb(): string
    {
        return $this->blurb;
    }

    public function partype(): string
    {
        return $this->partype;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return $this->tags;
    }

    public function image(): ChampionImage
    {
        return $this->image;
    }

    public function info(): ChampionInfo
    {
        return $this->info;
    }

    public function stats(): ChampionStats
    {
        return $this->stats;
    }

    /**
     * @return ChampionSkin[]
     */
    public function skins(): array
    {
        return $this->skins;
    }
}
