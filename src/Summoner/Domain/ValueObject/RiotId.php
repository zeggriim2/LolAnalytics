<?php

declare(strict_types=1);

namespace App\Summoner\Domain\ValueObject;

final readonly class RiotId
{
    private function __construct(
        private string $gameName,
        private string $tagLine,
    ) {
    }

    public static function create(string $gameName, string $tagLine): self
    {
        if (empty($gameName)) {
            throw new \InvalidArgumentException('Game name cannot be empty');
        }

        if (empty($tagLine)) {
            throw new \InvalidArgumentException('Tag line cannot be empty');
        }

        return new self($gameName, $tagLine);
    }

    public static function fromFullName(string $fullName): self
    {
        $parts = explode('#', $fullName, 2);

        if (2 !== count($parts)) {
            throw new \InvalidArgumentException('Invalid Riot ID format. Expected "GameName#TagLine"');
        }

        return self::create($parts[0], $parts[1]);
    }

    public function gameName(): string
    {
        return $this->gameName;
    }

    public function tagLine(): string
    {
        return $this->tagLine;
    }

    public function fullName(): string
    {
        return sprintf('%s#%s', $this->gameName, $this->tagLine);
    }

    public function __toString(): string
    {
        return $this->fullName();
    }

    public function equals(self $other): bool
    {
        return $this->gameName === $other->gameName
            && $this->tagLine === $other->tagLine;
    }
}
