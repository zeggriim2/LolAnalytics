<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class PerkStyleDto
{
    private string $description;
    /**
     * @var PerkStyleSelectionDto[]
     */
    private array $selections;
    private int $style;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return PerkStyleDto
     */
    public function setDescription(string $description): PerkStyleDto
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
     * @return PerkStyleDto
     */
    public function setSelections(array $selections): PerkStyleDto
    {
        $this->selections = $selections;
        return $this;
    }

    /**
     * @return int
     */
    public function getStyle(): int
    {
        return $this->style;
    }

    /**
     * @param int $style
     * @return PerkStyleDto
     */
    public function setStyle(int $style): PerkStyleDto
    {
        $this->style = $style;
        return $this;
    }
}