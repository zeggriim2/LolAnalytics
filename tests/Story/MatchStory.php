<?php

declare(strict_types=1);

namespace App\Tests\Story;

use App\Tests\Factory\MatchEntityFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'match')]
final class MatchStory extends Story
{
    public function build(): void
    {
        MatchEntityFactory::createOne();
    }
}
