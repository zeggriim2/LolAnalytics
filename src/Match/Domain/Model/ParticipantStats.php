<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

final class ParticipantStats
{
    /**
     * @param string[] $items
     */
    public function __construct(
        private readonly int $cs,
        private readonly int $goldEarned,
        private readonly int $totalDamageDealtToChampions,
        private readonly int $totalDamageTaken,
        private readonly int $visionScore,
        private readonly string $lane,
        private readonly string $individualPosition,
        private readonly int $summoner1Id,
        private readonly int $summoner2Id,
        private readonly int $champLevel,
        private readonly int $wardsPlaced,
        private readonly int $wardsKilled,
        private readonly bool $firstBloodKill,
        private readonly array $items,
    ) {
    }

    public static function empty(): self
    {
        return new self(
            cs: 0,
            goldEarned: 0,
            totalDamageDealtToChampions: 0,
            totalDamageTaken: 0,
            visionScore: 0,
            lane: '',
            individualPosition: '',
            summoner1Id: 0,
            summoner2Id: 0,
            champLevel: 1,
            wardsPlaced: 0,
            wardsKilled: 0,
            firstBloodKill: false,
            items: [],
        );
    }

    public function cs(): int
    {
        return $this->cs;
    }

    public function goldEarned(): int
    {
        return $this->goldEarned;
    }

    public function totalDamageDealtToChampions(): int
    {
        return $this->totalDamageDealtToChampions;
    }

    public function totalDamageTaken(): int
    {
        return $this->totalDamageTaken;
    }

    public function visionScore(): int
    {
        return $this->visionScore;
    }

    public function lane(): string
    {
        return $this->lane;
    }

    public function individualPosition(): string
    {
        return $this->individualPosition;
    }

    public function summoner1Id(): int
    {
        return $this->summoner1Id;
    }

    public function summoner2Id(): int
    {
        return $this->summoner2Id;
    }

    public function champLevel(): int
    {
        return $this->champLevel;
    }

    public function wardsPlaced(): int
    {
        return $this->wardsPlaced;
    }

    public function wardsKilled(): int
    {
        return $this->wardsKilled;
    }

    public function firstBloodKill(): bool
    {
        return $this->firstBloodKill;
    }

    /**
     * @return string[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
