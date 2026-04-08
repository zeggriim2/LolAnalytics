<?php

declare(strict_types=1);

namespace App\Tests\Story;

use App\Tests\Factory\LeagueEntityFactory;
use App\Tests\Factory\LeagueEntryEntityFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'league')]
final class LeagueStory extends Story
{
    public function build(): void
    {
        $challenger = LeagueEntityFactory::new()->challenger()->forPlatform('euw1')->create();
        LeagueEntryEntityFactory::createMany(10, ['league' => $challenger]);

        $grandmaster = LeagueEntityFactory::new()->grandmaster()->forPlatform('euw1')->create();
        LeagueEntryEntityFactory::createMany(5, ['league' => $grandmaster]);
    }
}
