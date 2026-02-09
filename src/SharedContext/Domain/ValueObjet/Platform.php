<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\ValueObjet;

enum Platform: string
{
    case BR1 = 'br1';
    case EUN1 = 'eun1';
    case EUW1 = 'euw1';
    case JP1 = 'jp1';
    case KR = 'kr';
    case LA1 = 'la1';
    case LA2 = 'la2';
    case ME1 = 'me1';
    case NA1 = 'na1';
    case OC1 = 'oc1';
    case PH2 = 'ph2';
    case RU = 'ru';
    case SG2 = 'sg2';
    case TH2 = 'th2';
    case TR1 = 'tr1';
    case TW2 = 'tw2';
    case VN2 = 'vn2';

    public function toRegion(): Region
    {
        return match ($this) {
            self::BR1, self::LA1, self::LA2, self::NA1 => Region::AMERICAS,
            self::JP1, self::KR, self::PH2, self::SG2, self::TH2, self::TW2, self::VN2 => Region::ASIA,
            self::EUN1, self::EUW1, self::ME1, self::RU, self::TR1 => Region::EUROPE,
            self::OC1 => Region::SEA,
        };
    }
}
