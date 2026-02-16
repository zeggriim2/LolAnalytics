<?php

declare(strict_types=1);

namespace App\Champion\Domain\Model;

final class ChampionImage
{
    public function __construct(
        private readonly string $full,
        private readonly string $sprite,
        private readonly string $group,
        private readonly int $x,
        private readonly int $y,
        private readonly int $w,
        private readonly int $h,
    ) {
    }

    public function full(): string
    {
        return $this->full;
    }

    public function sprite(): string
    {
        return $this->sprite;
    }

    public function group(): string
    {
        return $this->group;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    public function w(): int
    {
        return $this->w;
    }

    public function h(): int
    {
        return $this->h;
    }
}
