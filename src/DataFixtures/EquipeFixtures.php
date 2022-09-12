<?php

namespace App\DataFixtures;

use App\Entity\Competition;
use App\Entity\Equipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class EquipeFixtures extends Fixture implements FixtureGroupInterface
{

    private array $equipes = [
        [
            'name'          => 'G2 Esport',
            'shortName'     => 'G2',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'MAD Lions',
            'shortName'     => 'MAD',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Rogue',
            'shortName'     => 'RGE',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Misfits Gaming',
            'shortName'     => 'MSF',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Fnatic',
            'shortName'     => 'FNC',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'EXCEL',
            'shortName'     => 'XL',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Team Vitality',
            'shortName'     => 'VIT',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'SK Gaming',
            'shortName'     => 'MSF',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Astralis',
            'shortName'     => 'AST',
            'competition'   => [
                'LEC'
            ]
        ],
        [
            'name'          => 'Team BDS',
            'shortName'     => 'BDS',
            'competition'   => [
                'LEC'
            ]
        ]

    ];
    private array $competitions = [
        [
            'name'      => 'LEC',
            'annee'     => '2022-01-01'
        ],
        [
            'name'      => 'LCK',
            'annee'     => '2022-01-01'
        ],
        [
            'name'      => 'LFL',
            'annee'     => '2022-01-01'
        ]
    ];


    public function load(ObjectManager $manager): void
    {
        foreach ($this->equipes as $equipe) {
            $competition = $this->instanceEquipe($equipe['name'], $equipe['shortName']);
            $manager->persist($competition);
        }

        foreach ($this->competitions as $competition) {
            $competition = $this->instanceCompetition($competition['name'], $competition['annee']);
            $manager->persist($competition);
        }

        $manager->flush();
    }

    private function instanceEquipe(string $name,string $shortName): Equipe
    {
        return  (new Equipe())
            ->setName($name)
            ->setShortName($shortName)
        ;
    }

    private function instanceCompetition(string $name, string $annee)
    {
        return (new Competition())
            ->setName($name)
            ->setAnnee(new \DateTimeImmutable($annee))
            ;
    }

    public static function getGroups(): array
    {
        return ['equipe'];
    }
}
