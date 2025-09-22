<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistance\Doctrine\Entity;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Domain\ValueObjet\SummonerId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matches')]
final class MatchEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $matchId;

    #[ORM\Column(type: Types::STRING)]
    private string $region;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $playedAt;

    #[ORM\Column(type: Types::INTEGER)]
    private int $durationSeconds;

    #[ORM\Column(type: Types::JSON)]
    private array $participants = [];

    public static function fromDomain(Matche $match, string $region): self
    {
        $e = new self();
        $e->matchId = (string)$match->id();
        $e->region = $region;
        $e->playedAt = $match->playedAt();
        $e->durationSeconds = $match->durationSeconds();

        $participants = [];
        foreach ($match->participants() as $p) {
            $participants[] = [
                'summonerId' => (string)$p->summonerId(),
                'championId' => $p->championId(),
                'win' => $p->win(),
                'kda' => [ 'kills' => $p->kda()->kills(), 'deaths' => $p->kda()->deaths(), 'assists' => $p->kda()->assists() ],
                'items' => $p->items()
            ];
        }


        $e->participants = $participants;
        return $e;
    }


    public function toDomain(): Matche
    {
        $participants = [];
        foreach ($this->participants as $p) {
            $participants[] = new Participant(
                SummonerId::fromString($p['summonerId']),
                (int)$p['championId'],
                (bool)$p['win'],
                new KDA($p['kda']['kills'], $p['kda']['deaths'], $p['kda']['assists']),
                $p['items']
            );
        }


        return Matche::create(
            MatchId::fromString($this->matchId),
            new \DateTimeImmutable($this->playedAt->format('c')),
            (int)$this->durationSeconds,
            $participants
        );
    }

    public function getId(): ?int { return $this->id; }
    public function getMatchId(): string { return $this->matchId; }
    public function setMatchId(string $matchId): void { $this->matchId = $matchId; }
    public function setRegion(string $region): void { $this->region = $region; }
}
