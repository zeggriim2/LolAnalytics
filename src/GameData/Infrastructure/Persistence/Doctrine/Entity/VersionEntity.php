<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\Version;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_versions')]
class VersionEntity
{
    #[ORM\Id()]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $version;

    public static function fromDomain(Version $version): self
    {
        $entity = new self();
        $entity->version = $version->version();

        return $entity;
    }

    public function toDomain(): Version
    {
        return new Version(
            $this->version
        );
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}
