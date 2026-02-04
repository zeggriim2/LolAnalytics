<?php

declare(strict_types=1);

namespace App\Match\Domain\ValueObjet;

enum Platform: string
{
    case BR1 = 'br1';
    case EUN1 = 'eun1';
    case EUW1 = 'euw1';
    case JP1 = 'jp1';
    case KR = 'kr';
    case LA1 = 'la1';
    case LA2 = 'la2';
    case NA1 = 'na1';
    case OC1 = 'oc1';
    case TR1 = 'tr1';
    case RU = 'ru';
    case PH2 = 'ph2';
    case SG2 = 'sg2';
    case TH2 = 'th2';
    case TW2 = 'tw2';
    case VN2 = 'vn2';
}
