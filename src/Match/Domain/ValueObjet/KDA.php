<?php

declare(strict_types=1);

namespace App\Match\Domain\ValueObjet;

final class KDA
{
    public function __construct(
        private readonly int $kills,
        private readonly int $deaths,
        private readonly int $assists
    ) {
        if ($deaths < 0 || $kills < 0 || $assists < 0) {
            throw new \InvalidArgumentException('KDA parts must be >= 0');
        }
    }

    public function kills(): int
    {
        return $this->kills;
    }

    public function deaths(): int
    {
        return $this->deaths;
    }

    public function assists(): int
    {
        return $this->assists;
    }

    public function ratio(): float
    {
        return 0 === $this->deaths ? ($this->kills + $this->assists) : ($this->kills + $this->assists) / $this->deaths;
    }
}
