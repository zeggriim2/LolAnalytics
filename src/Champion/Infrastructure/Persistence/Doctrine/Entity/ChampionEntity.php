<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Entity;

use App\Champion\Domain\Model\Champion;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\VersionEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'champions')]
class ChampionEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $riotId;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: VersionEntity::class, inversedBy: 'champions')]
    #[ORM\JoinColumn(name: 'version', referencedColumnName: 'version', nullable: false)]
    private VersionEntity $version;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $championKey;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $blurb;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $partype;

    /** @var string[] */
    #[ORM\Column(type: Types::JSON)]
    private array $tags = [];

    #[ORM\OneToOne(targetEntity: ChampionInfoEntity::class, mappedBy: 'champion', cascade: ['persist', 'remove'])]
    private ChampionInfoEntity $info;

    #[ORM\OneToOne(targetEntity: ChampionStatsEntity::class, mappedBy: 'champion', cascade: ['persist', 'remove'])]
    private ChampionStatsEntity $stats;

    #[ORM\OneToOne(targetEntity: ChampionImageEntity::class, mappedBy: 'champion', cascade: ['persist', 'remove'])]
    private ChampionImageEntity $image;

    public static function fromDomain(Champion $champion): self
    {
        $e = new self();
        $e->riotId = $champion->riotId();
        $e->championKey = $champion->championKey();
        $e->name = $champion->name();
        $e->title = $champion->title();
        $e->blurb = $champion->blurb();
        $e->partype = $champion->partype();
        $e->tags = $champion->tags();

        $e->info = ChampionInfoEntity::fromDomain($champion->info(), $e);
        $e->stats = ChampionStatsEntity::fromDomain($champion->stats(), $e);
        $e->image = ChampionImageEntity::fromDomain($champion->image(), $e);

        return $e;
    }

    public function toDomain(): Champion
    {
        return Champion::create(
            riotId: $this->riotId,
            version: $this->version->getVersion(),
            championKey: $this->championKey,
            name: $this->name,
            title: $this->title,
            blurb: $this->blurb,
            partype: $this->partype,
            tags: $this->tags,
            image: $this->image->toDomain(),
            info: $this->info->toDomain(),
            stats: $this->stats->toDomain(),
        );
    }

    public function getRiotId(): string
    {
        return $this->riotId;
    }

    public function getVersion(): VersionEntity
    {
        return $this->version;
    }

    public function setVersion(VersionEntity $versionEntity): void
    {
        $this->version = $versionEntity;
    }
}
