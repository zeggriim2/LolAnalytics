<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class ParticipantDto
{
    private int $assists;
    private int $baronKills;
    private int $bountyLevel;
    private int $champExperience;
    private int $champLevel;
    private int $championId;
    private string $championName;
    private int $championTransform;
    private int $consumablesPurchased;
    private int $damageDealtToBuildings;
    private int $damageDealtToObjectives;
    private int $damageDealtToTurrets;
    private int $damageSelfMitigated;
    private int $deaths;
    private int $detectorWardsPlaced;
    private int $doubleKills;
    private int $dragonKills;
    private bool $firstBloodAssist;
    private bool $firstBloodKill;
    private bool $firstTowerAssist;
    private bool $firstTowerKill;
    private bool $gameEndedInEarlySurrender;
    private bool $gameEndedInSurrender;
    private int $goldEarned;
    private int $goldSpent;
    private string $individualPosition;
    private int $inhibitorKills;
    private int $inhibitorTakedowns;
    private int $inhibitorsLost;
    private int $item0;
    private int $item1;
    private int $item2;
    private int $item3;
    private int $item4;
    private int $item5;
    private int $item6;
    private int $itemsPurchased;
    private int $killingSprees;
    private int $kills;
    private string $lane;
    private int $largestCriticalStrike;
    private int $largestKillingSpree;
    private int $largestMultiKill;
    private int $longestTimeSpentLiving;
    private int $magicDamageDealt;
    private int $magicDamageDealtToChampions;
    private int $magicDamageTaken;
    private int $neutralMinionsKilled;
    private int $nexusKills;
    private int $nexusTakedowns;
    private int $nexusLost;
    private int $objectivesStolen;
    private int $objectivesStolenAssists;
    private int $participantId;
    private int $pentaKills;
    private PerksDto $perks;
    private int $physicalDamageDealt;
    private int $physicalDamageDealtToChampions;
    private int $physicalDamageTaken;
    private int $profileIcon;
    private string $puuid;
    private int $quadraKills;
    private string $riotIdName;
    private string $riotIdTagline;
    private string $role;
    private int $sightWardsBoughtInGame;
    private int $spell1Casts;
    private int $spell2Casts;
    private int $spell3Casts;
    private int $spell4Casts;
    private int $summoner1Casts;
    private int $summoner1Id;
    private int $summoner2Casts;
    private int $summoner2Id;
    private string $summonerId;
    private int $summonerLevel;
    private string $summonerName;
    private bool $teamEarlySurrendered;
    private int $teamId;
    private string $teamPosition;
    private int $timeCCingOthers;
    private int $timePlayed;
    private int $totalDamageDealt;
    private int $totalDamageDealtToChampions;
    private int $totalDamageShieldedOnTeammates;
    private int $totalDamageTaken;
    private int $totalHeal;
    private int $totalHealsOnTeammates;
    private int $totalMinionsKilled;
    private int $totalTimeCCDealt;
    private int $totalTimeSpentDead;
    private int $totalUnitsHealed;
    private int $tripleKills;
    private int $trueDamageDealt;
    private int $trueDamageDealtToChampions;
    private int $trueDamageTaken;
    private int $turretKills;
    private int $turretTakedowns;
    private int $turretsLost;
    private int $unrealKills;
    private int $visionScore;
    private int $visionWardsBoughtInGame;
    private int $wardsKilled;
    private int $wardsPlaced;
    private bool $win;

    /**
     * @return int
     */
    public function getAssists(): int
    {
        return $this->assists;
    }

    /**
     * @param int $assists
     * @return ParticipantDto
     */
    public function setAssists(int $assists): ParticipantDto
    {
        $this->assists = $assists;
        return $this;
    }

    /**
     * @return int
     */
    public function getBaronKills(): int
    {
        return $this->baronKills;
    }

    /**
     * @param int $baronKills
     * @return ParticipantDto
     */
    public function setBaronKills(int $baronKills): ParticipantDto
    {
        $this->baronKills = $baronKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getBountyLevel(): int
    {
        return $this->bountyLevel;
    }

    /**
     * @param int $bountyLevel
     * @return ParticipantDto
     */
    public function setBountyLevel(int $bountyLevel): ParticipantDto
    {
        $this->bountyLevel = $bountyLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampExperience(): int
    {
        return $this->champExperience;
    }

    /**
     * @param int $champExperience
     * @return ParticipantDto
     */
    public function setChampExperience(int $champExperience): ParticipantDto
    {
        $this->champExperience = $champExperience;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampLevel(): int
    {
        return $this->champLevel;
    }

    /**
     * @param int $champLevel
     * @return ParticipantDto
     */
    public function setChampLevel(int $champLevel): ParticipantDto
    {
        $this->champLevel = $champLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionId(): int
    {
        return $this->championId;
    }

    /**
     * @param int $championId
     * @return ParticipantDto
     */
    public function setChampionId(int $championId): ParticipantDto
    {
        $this->championId = $championId;
        return $this;
    }

    /**
     * @return string
     */
    public function getChampionName(): string
    {
        return $this->championName;
    }

    /**
     * @param string $championName
     * @return ParticipantDto
     */
    public function setChampionName(string $championName): ParticipantDto
    {
        $this->championName = $championName;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionTransform(): int
    {
        return $this->championTransform;
    }

    /**
     * @param int $championTransform
     * @return ParticipantDto
     */
    public function setChampionTransform(int $championTransform): ParticipantDto
    {
        $this->championTransform = $championTransform;
        return $this;
    }

    /**
     * @return int
     */
    public function getConsumablesPurchased(): int
    {
        return $this->consumablesPurchased;
    }

    /**
     * @param int $consumablesPurchased
     * @return ParticipantDto
     */
    public function setConsumablesPurchased(int $consumablesPurchased): ParticipantDto
    {
        $this->consumablesPurchased = $consumablesPurchased;
        return $this;
    }

    /**
     * @return int
     */
    public function getDamageDealtToBuildings(): int
    {
        return $this->damageDealtToBuildings;
    }

    /**
     * @param int $damageDealtToBuildings
     * @return ParticipantDto
     */
    public function setDamageDealtToBuildings(int $damageDealtToBuildings): ParticipantDto
    {
        $this->damageDealtToBuildings = $damageDealtToBuildings;
        return $this;
    }

    /**
     * @return int
     */
    public function getDamageDealtToObjectives(): int
    {
        return $this->damageDealtToObjectives;
    }

    /**
     * @param int $damageDealtToObjectives
     * @return ParticipantDto
     */
    public function setDamageDealtToObjectives(int $damageDealtToObjectives): ParticipantDto
    {
        $this->damageDealtToObjectives = $damageDealtToObjectives;
        return $this;
    }

    /**
     * @return int
     */
    public function getDamageDealtToTurrets(): int
    {
        return $this->damageDealtToTurrets;
    }

    /**
     * @param int $damageDealtToTurrets
     * @return ParticipantDto
     */
    public function setDamageDealtToTurrets(int $damageDealtToTurrets): ParticipantDto
    {
        $this->damageDealtToTurrets = $damageDealtToTurrets;
        return $this;
    }

    /**
     * @return int
     */
    public function getDamageSelfMitigated(): int
    {
        return $this->damageSelfMitigated;
    }

    /**
     * @param int $damageSelfMitigated
     * @return ParticipantDto
     */
    public function setDamageSelfMitigated(int $damageSelfMitigated): ParticipantDto
    {
        $this->damageSelfMitigated = $damageSelfMitigated;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeaths(): int
    {
        return $this->deaths;
    }

    /**
     * @param int $deaths
     * @return ParticipantDto
     */
    public function setDeaths(int $deaths): ParticipantDto
    {
        $this->deaths = $deaths;
        return $this;
    }

    /**
     * @return int
     */
    public function getDetectorWardsPlaced(): int
    {
        return $this->detectorWardsPlaced;
    }

    /**
     * @param int $detectorWardsPlaced
     * @return ParticipantDto
     */
    public function setDetectorWardsPlaced(int $detectorWardsPlaced): ParticipantDto
    {
        $this->detectorWardsPlaced = $detectorWardsPlaced;
        return $this;
    }

    /**
     * @return int
     */
    public function getDoubleKills(): int
    {
        return $this->doubleKills;
    }

    /**
     * @param int $doubleKills
     * @return ParticipantDto
     */
    public function setDoubleKills(int $doubleKills): ParticipantDto
    {
        $this->doubleKills = $doubleKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getDragonKills(): int
    {
        return $this->dragonKills;
    }

    /**
     * @param int $dragonKills
     * @return ParticipantDto
     */
    public function setDragonKills(int $dragonKills): ParticipantDto
    {
        $this->dragonKills = $dragonKills;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstBloodAssist(): bool
    {
        return $this->firstBloodAssist;
    }

    /**
     * @param bool $firstBloodAssist
     * @return ParticipantDto
     */
    public function setFirstBloodAssist(bool $firstBloodAssist): ParticipantDto
    {
        $this->firstBloodAssist = $firstBloodAssist;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstBloodKill(): bool
    {
        return $this->firstBloodKill;
    }

    /**
     * @param bool $firstBloodKill
     * @return ParticipantDto
     */
    public function setFirstBloodKill(bool $firstBloodKill): ParticipantDto
    {
        $this->firstBloodKill = $firstBloodKill;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstTowerAssist(): bool
    {
        return $this->firstTowerAssist;
    }

    /**
     * @param bool $firstTowerAssist
     * @return ParticipantDto
     */
    public function setFirstTowerAssist(bool $firstTowerAssist): ParticipantDto
    {
        $this->firstTowerAssist = $firstTowerAssist;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFirstTowerKill(): bool
    {
        return $this->firstTowerKill;
    }

    /**
     * @param bool $firstTowerKill
     * @return ParticipantDto
     */
    public function setFirstTowerKill(bool $firstTowerKill): ParticipantDto
    {
        $this->firstTowerKill = $firstTowerKill;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGameEndedInEarlySurrender(): bool
    {
        return $this->gameEndedInEarlySurrender;
    }

    /**
     * @param bool $gameEndedInEarlySurrender
     * @return ParticipantDto
     */
    public function setGameEndedInEarlySurrender(bool $gameEndedInEarlySurrender): ParticipantDto
    {
        $this->gameEndedInEarlySurrender = $gameEndedInEarlySurrender;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGameEndedInSurrender(): bool
    {
        return $this->gameEndedInSurrender;
    }

    /**
     * @param bool $gameEndedInSurrender
     * @return ParticipantDto
     */
    public function setGameEndedInSurrender(bool $gameEndedInSurrender): ParticipantDto
    {
        $this->gameEndedInSurrender = $gameEndedInSurrender;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoldEarned(): int
    {
        return $this->goldEarned;
    }

    /**
     * @param int $goldEarned
     * @return ParticipantDto
     */
    public function setGoldEarned(int $goldEarned): ParticipantDto
    {
        $this->goldEarned = $goldEarned;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoldSpent(): int
    {
        return $this->goldSpent;
    }

    /**
     * @param int $goldSpent
     * @return ParticipantDto
     */
    public function setGoldSpent(int $goldSpent): ParticipantDto
    {
        $this->goldSpent = $goldSpent;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndividualPosition(): string
    {
        return $this->individualPosition;
    }

    /**
     * @param string $individualPosition
     * @return ParticipantDto
     */
    public function setIndividualPosition(string $individualPosition): ParticipantDto
    {
        $this->individualPosition = $individualPosition;
        return $this;
    }

    /**
     * @return int
     */
    public function getInhibitorKills(): int
    {
        return $this->inhibitorKills;
    }

    /**
     * @param int $inhibitorKills
     * @return ParticipantDto
     */
    public function setInhibitorKills(int $inhibitorKills): ParticipantDto
    {
        $this->inhibitorKills = $inhibitorKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getInhibitorTakedowns(): int
    {
        return $this->inhibitorTakedowns;
    }

    /**
     * @param int $inhibitorTakedowns
     * @return ParticipantDto
     */
    public function setInhibitorTakedowns(int $inhibitorTakedowns): ParticipantDto
    {
        $this->inhibitorTakedowns = $inhibitorTakedowns;
        return $this;
    }

    /**
     * @return int
     */
    public function getInhibitorsLost(): int
    {
        return $this->inhibitorsLost;
    }

    /**
     * @param int $inhibitorsLost
     * @return ParticipantDto
     */
    public function setInhibitorsLost(int $inhibitorsLost): ParticipantDto
    {
        $this->inhibitorsLost = $inhibitorsLost;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem0(): int
    {
        return $this->item0;
    }

    /**
     * @param int $item0
     * @return ParticipantDto
     */
    public function setItem0(int $item0): ParticipantDto
    {
        $this->item0 = $item0;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem1(): int
    {
        return $this->item1;
    }

    /**
     * @param int $item1
     * @return ParticipantDto
     */
    public function setItem1(int $item1): ParticipantDto
    {
        $this->item1 = $item1;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem2(): int
    {
        return $this->item2;
    }

    /**
     * @param int $item2
     * @return ParticipantDto
     */
    public function setItem2(int $item2): ParticipantDto
    {
        $this->item2 = $item2;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem3(): int
    {
        return $this->item3;
    }

    /**
     * @param int $item3
     * @return ParticipantDto
     */
    public function setItem3(int $item3): ParticipantDto
    {
        $this->item3 = $item3;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem4(): int
    {
        return $this->item4;
    }

    /**
     * @param int $item4
     * @return ParticipantDto
     */
    public function setItem4(int $item4): ParticipantDto
    {
        $this->item4 = $item4;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem5(): int
    {
        return $this->item5;
    }

    /**
     * @param int $item5
     * @return ParticipantDto
     */
    public function setItem5(int $item5): ParticipantDto
    {
        $this->item5 = $item5;
        return $this;
    }

    /**
     * @return int
     */
    public function getItem6(): int
    {
        return $this->item6;
    }

    /**
     * @param int $item6
     * @return ParticipantDto
     */
    public function setItem6(int $item6): ParticipantDto
    {
        $this->item6 = $item6;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemsPurchased(): int
    {
        return $this->itemsPurchased;
    }

    /**
     * @param int $itemsPurchased
     * @return ParticipantDto
     */
    public function setItemsPurchased(int $itemsPurchased): ParticipantDto
    {
        $this->itemsPurchased = $itemsPurchased;
        return $this;
    }

    /**
     * @return int
     */
    public function getKillingSprees(): int
    {
        return $this->killingSprees;
    }

    /**
     * @param int $killingSprees
     * @return ParticipantDto
     */
    public function setKillingSprees(int $killingSprees): ParticipantDto
    {
        $this->killingSprees = $killingSprees;
        return $this;
    }

    /**
     * @return int
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * @param int $kills
     * @return ParticipantDto
     */
    public function setKills(int $kills): ParticipantDto
    {
        $this->kills = $kills;
        return $this;
    }

    /**
     * @return string
     */
    public function getLane(): string
    {
        return $this->lane;
    }

    /**
     * @param string $lane
     * @return ParticipantDto
     */
    public function setLane(string $lane): ParticipantDto
    {
        $this->lane = $lane;
        return $this;
    }

    /**
     * @return int
     */
    public function getLargestCriticalStrike(): int
    {
        return $this->largestCriticalStrike;
    }

    /**
     * @param int $largestCriticalStrike
     * @return ParticipantDto
     */
    public function setLargestCriticalStrike(int $largestCriticalStrike): ParticipantDto
    {
        $this->largestCriticalStrike = $largestCriticalStrike;
        return $this;
    }

    /**
     * @return int
     */
    public function getLargestKillingSpree(): int
    {
        return $this->largestKillingSpree;
    }

    /**
     * @param int $largestKillingSpree
     * @return ParticipantDto
     */
    public function setLargestKillingSpree(int $largestKillingSpree): ParticipantDto
    {
        $this->largestKillingSpree = $largestKillingSpree;
        return $this;
    }

    /**
     * @return int
     */
    public function getLargestMultiKill(): int
    {
        return $this->largestMultiKill;
    }

    /**
     * @param int $largestMultiKill
     * @return ParticipantDto
     */
    public function setLargestMultiKill(int $largestMultiKill): ParticipantDto
    {
        $this->largestMultiKill = $largestMultiKill;
        return $this;
    }

    /**
     * @return int
     */
    public function getLongestTimeSpentLiving(): int
    {
        return $this->longestTimeSpentLiving;
    }

    /**
     * @param int $longestTimeSpentLiving
     * @return ParticipantDto
     */
    public function setLongestTimeSpentLiving(int $longestTimeSpentLiving): ParticipantDto
    {
        $this->longestTimeSpentLiving = $longestTimeSpentLiving;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagicDamageDealt(): int
    {
        return $this->magicDamageDealt;
    }

    /**
     * @param int $magicDamageDealt
     * @return ParticipantDto
     */
    public function setMagicDamageDealt(int $magicDamageDealt): ParticipantDto
    {
        $this->magicDamageDealt = $magicDamageDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagicDamageDealtToChampions(): int
    {
        return $this->magicDamageDealtToChampions;
    }

    /**
     * @param int $magicDamageDealtToChampions
     * @return ParticipantDto
     */
    public function setMagicDamageDealtToChampions(int $magicDamageDealtToChampions): ParticipantDto
    {
        $this->magicDamageDealtToChampions = $magicDamageDealtToChampions;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagicDamageTaken(): int
    {
        return $this->magicDamageTaken;
    }

    /**
     * @param int $magicDamageTaken
     * @return ParticipantDto
     */
    public function setMagicDamageTaken(int $magicDamageTaken): ParticipantDto
    {
        $this->magicDamageTaken = $magicDamageTaken;
        return $this;
    }

    /**
     * @return int
     */
    public function getNeutralMinionsKilled(): int
    {
        return $this->neutralMinionsKilled;
    }

    /**
     * @param int $neutralMinionsKilled
     * @return ParticipantDto
     */
    public function setNeutralMinionsKilled(int $neutralMinionsKilled): ParticipantDto
    {
        $this->neutralMinionsKilled = $neutralMinionsKilled;
        return $this;
    }

    /**
     * @return int
     */
    public function getNexusKills(): int
    {
        return $this->nexusKills;
    }

    /**
     * @param int $nexusKills
     * @return ParticipantDto
     */
    public function setNexusKills(int $nexusKills): ParticipantDto
    {
        $this->nexusKills = $nexusKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getNexusTakedowns(): int
    {
        return $this->nexusTakedowns;
    }

    /**
     * @param int $nexusTakedowns
     * @return ParticipantDto
     */
    public function setNexusTakedowns(int $nexusTakedowns): ParticipantDto
    {
        $this->nexusTakedowns = $nexusTakedowns;
        return $this;
    }

    /**
     * @return int
     */
    public function getNexusLost(): int
    {
        return $this->nexusLost;
    }

    /**
     * @param int $nexusLost
     * @return ParticipantDto
     */
    public function setNexusLost(int $nexusLost): ParticipantDto
    {
        $this->nexusLost = $nexusLost;
        return $this;
    }

    /**
     * @return int
     */
    public function getObjectivesStolen(): int
    {
        return $this->objectivesStolen;
    }

    /**
     * @param int $objectivesStolen
     * @return ParticipantDto
     */
    public function setObjectivesStolen(int $objectivesStolen): ParticipantDto
    {
        $this->objectivesStolen = $objectivesStolen;
        return $this;
    }

    /**
     * @return int
     */
    public function getObjectivesStolenAssists(): int
    {
        return $this->objectivesStolenAssists;
    }

    /**
     * @param int $objectivesStolenAssists
     * @return ParticipantDto
     */
    public function setObjectivesStolenAssists(int $objectivesStolenAssists): ParticipantDto
    {
        $this->objectivesStolenAssists = $objectivesStolenAssists;
        return $this;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @param int $participantId
     * @return ParticipantDto
     */
    public function setParticipantId(int $participantId): ParticipantDto
    {
        $this->participantId = $participantId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPentaKills(): int
    {
        return $this->pentaKills;
    }

    /**
     * @param int $pentaKills
     * @return ParticipantDto
     */
    public function setPentaKills(int $pentaKills): ParticipantDto
    {
        $this->pentaKills = $pentaKills;
        return $this;
    }

    /**
     * @return PerksDto
     */
    public function getPerks(): PerksDto
    {
        return $this->perks;
    }

    /**
     * @param PerksDto $perks
     * @return ParticipantDto
     */
    public function setPerks(PerksDto $perks): ParticipantDto
    {
        $this->perks = $perks;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhysicalDamageDealt(): int
    {
        return $this->physicalDamageDealt;
    }

    /**
     * @param int $physicalDamageDealt
     * @return ParticipantDto
     */
    public function setPhysicalDamageDealt(int $physicalDamageDealt): ParticipantDto
    {
        $this->physicalDamageDealt = $physicalDamageDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhysicalDamageDealtToChampions(): int
    {
        return $this->physicalDamageDealtToChampions;
    }

    /**
     * @param int $physicalDamageDealtToChampions
     * @return ParticipantDto
     */
    public function setPhysicalDamageDealtToChampions(int $physicalDamageDealtToChampions): ParticipantDto
    {
        $this->physicalDamageDealtToChampions = $physicalDamageDealtToChampions;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhysicalDamageTaken(): int
    {
        return $this->physicalDamageTaken;
    }

    /**
     * @param int $physicalDamageTaken
     * @return ParticipantDto
     */
    public function setPhysicalDamageTaken(int $physicalDamageTaken): ParticipantDto
    {
        $this->physicalDamageTaken = $physicalDamageTaken;
        return $this;
    }

    /**
     * @return int
     */
    public function getProfileIcon(): int
    {
        return $this->profileIcon;
    }

    /**
     * @param int $profileIcon
     * @return ParticipantDto
     */
    public function setProfileIcon(int $profileIcon): ParticipantDto
    {
        $this->profileIcon = $profileIcon;
        return $this;
    }

    /**
     * @return string
     */
    public function getPuuid(): string
    {
        return $this->puuid;
    }

    /**
     * @param string $puuid
     * @return ParticipantDto
     */
    public function setPuuid(string $puuid): ParticipantDto
    {
        $this->puuid = $puuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuadraKills(): int
    {
        return $this->quadraKills;
    }

    /**
     * @param int $quadraKills
     * @return ParticipantDto
     */
    public function setQuadraKills(int $quadraKills): ParticipantDto
    {
        $this->quadraKills = $quadraKills;
        return $this;
    }

    /**
     * @return string
     */
    public function getRiotIdName(): string
    {
        return $this->riotIdName;
    }

    /**
     * @param string $riotIdName
     * @return ParticipantDto
     */
    public function setRiotIdName(string $riotIdName): ParticipantDto
    {
        $this->riotIdName = $riotIdName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRiotIdTagline(): string
    {
        return $this->riotIdTagline;
    }

    /**
     * @param string $riotIdTagline
     * @return ParticipantDto
     */
    public function setRiotIdTagline(string $riotIdTagline): ParticipantDto
    {
        $this->riotIdTagline = $riotIdTagline;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return ParticipantDto
     */
    public function setRole(string $role): ParticipantDto
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getSightWardsBoughtInGame(): int
    {
        return $this->sightWardsBoughtInGame;
    }

    /**
     * @param int $sightWardsBoughtInGame
     * @return ParticipantDto
     */
    public function setSightWardsBoughtInGame(int $sightWardsBoughtInGame): ParticipantDto
    {
        $this->sightWardsBoughtInGame = $sightWardsBoughtInGame;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpell1Casts(): int
    {
        return $this->spell1Casts;
    }

    /**
     * @param int $spell1Casts
     * @return ParticipantDto
     */
    public function setSpell1Casts(int $spell1Casts): ParticipantDto
    {
        $this->spell1Casts = $spell1Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpell2Casts(): int
    {
        return $this->spell2Casts;
    }

    /**
     * @param int $spell2Casts
     * @return ParticipantDto
     */
    public function setSpell2Casts(int $spell2Casts): ParticipantDto
    {
        $this->spell2Casts = $spell2Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpell3Casts(): int
    {
        return $this->spell3Casts;
    }

    /**
     * @param int $spell3Casts
     * @return ParticipantDto
     */
    public function setSpell3Casts(int $spell3Casts): ParticipantDto
    {
        $this->spell3Casts = $spell3Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpell4Casts(): int
    {
        return $this->spell4Casts;
    }

    /**
     * @param int $spell4Casts
     * @return ParticipantDto
     */
    public function setSpell4Casts(int $spell4Casts): ParticipantDto
    {
        $this->spell4Casts = $spell4Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSummoner1Casts(): int
    {
        return $this->summoner1Casts;
    }

    /**
     * @param int $summoner1Casts
     * @return ParticipantDto
     */
    public function setSummoner1Casts(int $summoner1Casts): ParticipantDto
    {
        $this->summoner1Casts = $summoner1Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSummoner1Id(): int
    {
        return $this->summoner1Id;
    }

    /**
     * @param int $summoner1Id
     * @return ParticipantDto
     */
    public function setSummoner1Id(int $summoner1Id): ParticipantDto
    {
        $this->summoner1Id = $summoner1Id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSummoner2Casts(): int
    {
        return $this->summoner2Casts;
    }

    /**
     * @param int $summoner2Casts
     * @return ParticipantDto
     */
    public function setSummoner2Casts(int $summoner2Casts): ParticipantDto
    {
        $this->summoner2Casts = $summoner2Casts;
        return $this;
    }

    /**
     * @return int
     */
    public function getSummoner2Id(): int
    {
        return $this->summoner2Id;
    }

    /**
     * @param int $summoner2Id
     * @return ParticipantDto
     */
    public function setSummoner2Id(int $summoner2Id): ParticipantDto
    {
        $this->summoner2Id = $summoner2Id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    /**
     * @param string $summonerId
     * @return ParticipantDto
     */
    public function setSummonerId(string $summonerId): ParticipantDto
    {
        $this->summonerId = $summonerId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSummonerLevel(): int
    {
        return $this->summonerLevel;
    }

    /**
     * @param int $summonerLevel
     * @return ParticipantDto
     */
    public function setSummonerLevel(int $summonerLevel): ParticipantDto
    {
        $this->summonerLevel = $summonerLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    /**
     * @param string $summonerName
     * @return ParticipantDto
     */
    public function setSummonerName(string $summonerName): ParticipantDto
    {
        $this->summonerName = $summonerName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTeamEarlySurrendered(): bool
    {
        return $this->teamEarlySurrendered;
    }

    /**
     * @param bool $teamEarlySurrendered
     * @return ParticipantDto
     */
    public function setTeamEarlySurrendered(bool $teamEarlySurrendered): ParticipantDto
    {
        $this->teamEarlySurrendered = $teamEarlySurrendered;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->teamId;
    }

    /**
     * @param int $teamId
     * @return ParticipantDto
     */
    public function setTeamId(int $teamId): ParticipantDto
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeamPosition(): string
    {
        return $this->teamPosition;
    }

    /**
     * @param string $teamPosition
     * @return ParticipantDto
     */
    public function setTeamPosition(string $teamPosition): ParticipantDto
    {
        $this->teamPosition = $teamPosition;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeCCingOthers(): int
    {
        return $this->timeCCingOthers;
    }

    /**
     * @param int $timeCCingOthers
     * @return ParticipantDto
     */
    public function setTimeCCingOthers(int $timeCCingOthers): ParticipantDto
    {
        $this->timeCCingOthers = $timeCCingOthers;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    /**
     * @param int $timePlayed
     * @return ParticipantDto
     */
    public function setTimePlayed(int $timePlayed): ParticipantDto
    {
        $this->timePlayed = $timePlayed;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalDamageDealt(): int
    {
        return $this->totalDamageDealt;
    }

    /**
     * @param int $totalDamageDealt
     * @return ParticipantDto
     */
    public function setTotalDamageDealt(int $totalDamageDealt): ParticipantDto
    {
        $this->totalDamageDealt = $totalDamageDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalDamageDealtToChampions(): int
    {
        return $this->totalDamageDealtToChampions;
    }

    /**
     * @param int $totalDamageDealtToChampions
     * @return ParticipantDto
     */
    public function setTotalDamageDealtToChampions(int $totalDamageDealtToChampions): ParticipantDto
    {
        $this->totalDamageDealtToChampions = $totalDamageDealtToChampions;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalDamageShieldedOnTeammates(): int
    {
        return $this->totalDamageShieldedOnTeammates;
    }

    /**
     * @param int $totalDamageShieldedOnTeammates
     * @return ParticipantDto
     */
    public function setTotalDamageShieldedOnTeammates(int $totalDamageShieldedOnTeammates): ParticipantDto
    {
        $this->totalDamageShieldedOnTeammates = $totalDamageShieldedOnTeammates;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalDamageTaken(): int
    {
        return $this->totalDamageTaken;
    }

    /**
     * @param int $totalDamageTaken
     * @return ParticipantDto
     */
    public function setTotalDamageTaken(int $totalDamageTaken): ParticipantDto
    {
        $this->totalDamageTaken = $totalDamageTaken;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalHeal(): int
    {
        return $this->totalHeal;
    }

    /**
     * @param int $totalHeal
     * @return ParticipantDto
     */
    public function setTotalHeal(int $totalHeal): ParticipantDto
    {
        $this->totalHeal = $totalHeal;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalHealsOnTeammates(): int
    {
        return $this->totalHealsOnTeammates;
    }

    /**
     * @param int $totalHealsOnTeammates
     * @return ParticipantDto
     */
    public function setTotalHealsOnTeammates(int $totalHealsOnTeammates): ParticipantDto
    {
        $this->totalHealsOnTeammates = $totalHealsOnTeammates;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalMinionsKilled(): int
    {
        return $this->totalMinionsKilled;
    }

    /**
     * @param int $totalMinionsKilled
     * @return ParticipantDto
     */
    public function setTotalMinionsKilled(int $totalMinionsKilled): ParticipantDto
    {
        $this->totalMinionsKilled = $totalMinionsKilled;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTimeCCDealt(): int
    {
        return $this->totalTimeCCDealt;
    }

    /**
     * @param int $totalTimeCCDealt
     * @return ParticipantDto
     */
    public function setTotalTimeCCDealt(int $totalTimeCCDealt): ParticipantDto
    {
        $this->totalTimeCCDealt = $totalTimeCCDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTimeSpentDead(): int
    {
        return $this->totalTimeSpentDead;
    }

    /**
     * @param int $totalTimeSpentDead
     * @return ParticipantDto
     */
    public function setTotalTimeSpentDead(int $totalTimeSpentDead): ParticipantDto
    {
        $this->totalTimeSpentDead = $totalTimeSpentDead;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalUnitsHealed(): int
    {
        return $this->totalUnitsHealed;
    }

    /**
     * @param int $totalUnitsHealed
     * @return ParticipantDto
     */
    public function setTotalUnitsHealed(int $totalUnitsHealed): ParticipantDto
    {
        $this->totalUnitsHealed = $totalUnitsHealed;
        return $this;
    }

    /**
     * @return int
     */
    public function getTripleKills(): int
    {
        return $this->tripleKills;
    }

    /**
     * @param int $tripleKills
     * @return ParticipantDto
     */
    public function setTripleKills(int $tripleKills): ParticipantDto
    {
        $this->tripleKills = $tripleKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getTrueDamageDealt(): int
    {
        return $this->trueDamageDealt;
    }

    /**
     * @param int $trueDamageDealt
     * @return ParticipantDto
     */
    public function setTrueDamageDealt(int $trueDamageDealt): ParticipantDto
    {
        $this->trueDamageDealt = $trueDamageDealt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTrueDamageDealtToChampions(): int
    {
        return $this->trueDamageDealtToChampions;
    }

    /**
     * @param int $trueDamageDealtToChampions
     * @return ParticipantDto
     */
    public function setTrueDamageDealtToChampions(int $trueDamageDealtToChampions): ParticipantDto
    {
        $this->trueDamageDealtToChampions = $trueDamageDealtToChampions;
        return $this;
    }

    /**
     * @return int
     */
    public function getTrueDamageTaken(): int
    {
        return $this->trueDamageTaken;
    }

    /**
     * @param int $trueDamageTaken
     * @return ParticipantDto
     */
    public function setTrueDamageTaken(int $trueDamageTaken): ParticipantDto
    {
        $this->trueDamageTaken = $trueDamageTaken;
        return $this;
    }

    /**
     * @return int
     */
    public function getTurretKills(): int
    {
        return $this->turretKills;
    }

    /**
     * @param int $turretKills
     * @return ParticipantDto
     */
    public function setTurretKills(int $turretKills): ParticipantDto
    {
        $this->turretKills = $turretKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getTurretTakedowns(): int
    {
        return $this->turretTakedowns;
    }

    /**
     * @param int $turretTakedowns
     * @return ParticipantDto
     */
    public function setTurretTakedowns(int $turretTakedowns): ParticipantDto
    {
        $this->turretTakedowns = $turretTakedowns;
        return $this;
    }

    /**
     * @return int
     */
    public function getTurretsLost(): int
    {
        return $this->turretsLost;
    }

    /**
     * @param int $turretsLost
     * @return ParticipantDto
     */
    public function setTurretsLost(int $turretsLost): ParticipantDto
    {
        $this->turretsLost = $turretsLost;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnrealKills(): int
    {
        return $this->unrealKills;
    }

    /**
     * @param int $unrealKills
     * @return ParticipantDto
     */
    public function setUnrealKills(int $unrealKills): ParticipantDto
    {
        $this->unrealKills = $unrealKills;
        return $this;
    }

    /**
     * @return int
     */
    public function getVisionScore(): int
    {
        return $this->visionScore;
    }

    /**
     * @param int $visionScore
     * @return ParticipantDto
     */
    public function setVisionScore(int $visionScore): ParticipantDto
    {
        $this->visionScore = $visionScore;
        return $this;
    }

    /**
     * @return int
     */
    public function getVisionWardsBoughtInGame(): int
    {
        return $this->visionWardsBoughtInGame;
    }

    /**
     * @param int $visionWardsBoughtInGame
     * @return ParticipantDto
     */
    public function setVisionWardsBoughtInGame(int $visionWardsBoughtInGame): ParticipantDto
    {
        $this->visionWardsBoughtInGame = $visionWardsBoughtInGame;
        return $this;
    }

    /**
     * @return int
     */
    public function getWardsKilled(): int
    {
        return $this->wardsKilled;
    }

    /**
     * @param int $wardsKilled
     * @return ParticipantDto
     */
    public function setWardsKilled(int $wardsKilled): ParticipantDto
    {
        $this->wardsKilled = $wardsKilled;
        return $this;
    }

    /**
     * @return int
     */
    public function getWardsPlaced(): int
    {
        return $this->wardsPlaced;
    }

    /**
     * @param int $wardsPlaced
     * @return ParticipantDto
     */
    public function setWardsPlaced(int $wardsPlaced): ParticipantDto
    {
        $this->wardsPlaced = $wardsPlaced;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWin(): bool
    {
        return $this->win;
    }

    /**
     * @param bool $win
     * @return ParticipantDto
     */
    public function setWin(bool $win): ParticipantDto
    {
        $this->win = $win;
        return $this;
    }
}