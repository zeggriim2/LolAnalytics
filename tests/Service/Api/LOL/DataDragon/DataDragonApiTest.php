<?php

declare(strict_types=1);

namespace App\Tests\Service\Api\LOL\DataDragon;

use App\Services\API\LOL\DataDragon\DataDragonApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DataDragonApiTest extends KernelTestCase
{
    private DataDragonApi $dataDragon;

    protected function setUp(): void
    {
        $this->dataDragon = static::getContainer()->get(DataDragonApi::class);
    }

    public function testGetVersions(): void
    {
        $versions = $this->dataDragon->getVersions();
        $this->assertIsArray($versions);
    }

    public function testGetLanguages(): void
    {
        $languages = $this->dataDragon->getLanguages();
        $this->assertIsArray($languages);
    }

    public function testGetChampions(): void
    {
        $champions = $this->dataDragon->getChampions();
        $this->assertIsArray($champions);
    }

    /**
     * @dataProvider providerTestChampion
     */
    public function testGetChampion($name): void
    {
        $champion = $this->dataDragon->getChampion($name);
        $this->assertIsArray($champion);
    }

    public function testGetItems(): void
    {
        $items = $this->dataDragon->getItems();
        $this->assertIsArray($items);
    }

    public function testGetSummoner(): void
    {
        $items = $this->dataDragon->getSummoner($this->dataDragon->getLastVersion());
        $this->assertIsArray($items);
    }

    private function providerTestChampion()
    {
        return [
            ['aatrox'],
            ['warwick'],
            ['zeri'],
        ];
    }
}
