<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStyleDto
{
    private string $description;
    /**
     * @var PerkStyleSelectionDto[]
     */
    private array $selections;
    private int $style;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return PerkStyleSelectionDto[]
     */
    public function getSelections(): array
    {
        return $this->selections;
    }

    /**
     * @param PerkStyleSelectionDto[] $selections
     */
    public function setSelections(array $selections): self
    {
        $this->selections = $selections;

        return $this;
    }

    public function getStyle(): int
    {
        return $this->style;
    }

    public function setStyle(int $style): self
    {
        $this->style = $style;

        return $this;
    }
}
