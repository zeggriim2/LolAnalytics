<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\Match\BanDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\InfoDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MetadataDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\ObjectiveDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\ObjectivesDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\ParticipantDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\PerksDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\PerkStatsDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\PerkStyleDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\PerkStyleSelectionDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\TeamDto;

trait CheckMatchApi
{

    private function checkMetadataDto(MetadataDto $metadataDto): void
    {
        $this->assertIsString($metadataDto->getMatchId());
        $this->assertIsString($metadataDto->getDataVersion());
        $this->assertIsArray($metadataDto->getParticipants());
    }

    private function checkInfoDto(InfoDto $infoDto): void
    {
        $this->assertIsInt($infoDto->getGameDuration());
        $this->assertIsInt($infoDto->getGameCreation());
        $this->assertIsInt($infoDto->getMapId());
        $this->assertIsInt($infoDto->getGameId());
        $this->assertIsInt($infoDto->getGameEndTimestamp());
        $this->assertIsInt($infoDto->getGameStartTimestamp());
        $this->assertIsInt($infoDto->getQueueId());
        $this->assertIsString($infoDto->getGameMode());
        $this->assertIsString($infoDto->getGameName());
        $this->assertIsString($infoDto->getGameType());
        $this->assertIsString($infoDto->getGameVersion());
        $this->assertIsString($infoDto->getPlatformId());
        $this->assertIsString($infoDto->getTournamentCode());
        if($infoDto->getTeams() > 0) {
            foreach ($infoDto->getTeams() as $team) {
                $this->assertInstanceOf(TeamDto::class, $team);
            }
            $this->checkTeamDto($infoDto->getTeams()[0]);
        }

        if( $infoDto->getParticipants() > 0) {
            foreach ( $infoDto->getParticipants() as $participant) {
                $this->assertInstanceOf(ParticipantDto::class, $participant);
            }
            $this->checkParticipantDto($infoDto->getParticipants()[0]);
        }
    }

    private function checkParticipantDto(ParticipantDto $participantDto): void
    {
        $this->assertIsInt($participantDto->getAssists());
        $this->assertIsInt($participantDto->getBaronKills());
        $this->assertIsInt($participantDto->getBountyLevel());
        $this->assertIsInt($participantDto->getChampExperience());
        $this->assertIsInt($participantDto->getChampLevel());
        $this->assertIsInt($participantDto->getChampionId());
        $this->assertIsString($participantDto->getChampionName());
        $this->assertIsInt($participantDto->getChampionTransform());
        $this->assertIsInt($participantDto->getConsumablesPurchased());
        $this->assertIsInt($participantDto->getDamageDealtToBuildings());
        $this->assertIsInt($participantDto->getDamageDealtToObjectives());
        $this->assertIsInt($participantDto->getDamageDealtToTurrets());
        $this->assertIsInt($participantDto->getDamageSelfMitigated());
        $this->assertIsInt($participantDto->getDeaths());
        $this->assertIsInt($participantDto->getDetectorWardsPlaced());
        $this->assertIsInt($participantDto->getDoubleKills());
        $this->assertIsInt($participantDto->getDragonKills());
        $this->assertIsBool($participantDto->isFirstBloodAssist());
        $this->assertIsBool($participantDto->isFirstBloodAssist());
        $this->assertIsBool($participantDto->isFirstBloodKill());
        $this->assertIsBool($participantDto->isFirstTowerAssist());
        $this->assertIsBool($participantDto->isFirstTowerKill());
        $this->assertIsBool($participantDto->isGameEndedInEarlySurrender());
        $this->assertIsBool($participantDto->isGameEndedInSurrender());
        $this->assertIsInt($participantDto->getGoldEarned());
        $this->assertIsInt($participantDto->getGoldSpent());
        $this->assertIsString($participantDto->getIndividualPosition());
        $this->assertIsInt($participantDto->getInhibitorKills());
        $this->assertIsInt($participantDto->getInhibitorTakedowns());
        $this->assertIsInt($participantDto->getInhibitorsLost());
        $this->assertIsInt($participantDto->getItem0());
        $this->assertIsInt($participantDto->getItem1());
        $this->assertIsInt($participantDto->getItem2());
        $this->assertIsInt($participantDto->getItem3());
        $this->assertIsInt($participantDto->getItem4());
        $this->assertIsInt($participantDto->getItem5());
        $this->assertIsInt($participantDto->getItem6());
        $this->assertIsInt($participantDto->getItemsPurchased());
        $this->assertIsInt($participantDto->getKillingSprees());
        $this->assertIsInt($participantDto->getKills());
        $this->assertIsString($participantDto->getLane());
        $this->assertIsInt($participantDto->getLargestCriticalStrike());
        $this->assertIsInt($participantDto->getLargestKillingSpree());
        $this->assertIsInt($participantDto->getLargestMultiKill());
        $this->assertIsInt($participantDto->getLongestTimeSpentLiving());
        $this->assertIsInt($participantDto->getMagicDamageDealt());
        $this->assertIsInt($participantDto->getMagicDamageDealtToChampions());
        $this->assertIsInt($participantDto->getMagicDamageTaken());
        $this->assertIsInt($participantDto->getNeutralMinionsKilled());
        $this->assertIsInt($participantDto->getNexusKills());
        $this->assertIsInt($participantDto->getNexusTakedowns());
        $this->assertIsInt($participantDto->getNexusLost());
        $this->assertIsInt($participantDto->getObjectivesStolen());
        $this->assertIsInt($participantDto->getObjectivesStolenAssists());
        $this->assertIsInt($participantDto->getParticipantId());
        $this->assertIsInt($participantDto->getPentaKills());
        $this->assertInstanceOf(PerksDto::class, $participantDto->getPerks());
        $this->checkPerksDto($participantDto->getPerks());
        $this->assertIsInt($participantDto->getPhysicalDamageDealt());
        $this->assertIsInt($participantDto->getPhysicalDamageDealtToChampions());
        $this->assertIsInt($participantDto->getPhysicalDamageTaken());
        $this->assertIsInt($participantDto->getProfileIcon());
        $this->assertIsString($participantDto->getPuuid());
        $this->assertIsInt($participantDto->getQuadraKills());
        $this->assertIsString($participantDto->getRiotIdName());
        $this->assertIsString($participantDto->getRiotIdTagline());
        $this->assertIsString($participantDto->getRole());
        $this->assertIsInt($participantDto->getSightWardsBoughtInGame());
        $this->assertIsInt($participantDto->getSpell1Casts());
        $this->assertIsInt($participantDto->getSpell2Casts());
        $this->assertIsInt($participantDto->getSpell3Casts());
        $this->assertIsInt($participantDto->getSpell4Casts());
        $this->assertIsInt($participantDto->getSummoner1Casts());
        $this->assertIsInt($participantDto->getSummoner1Id());
        $this->assertIsInt($participantDto->getSummoner2Casts());
        $this->assertIsInt($participantDto->getSummoner2Id());
        $this->assertIsString($participantDto->getSummonerId());
        $this->assertIsInt($participantDto->getSummonerLevel());
        $this->assertIsstring($participantDto->getSummonerName());
        $this->assertIsBool($participantDto->isTeamEarlySurrendered());
        $this->assertIsInt($participantDto->getTeamId());
        $this->assertIsString($participantDto->getTeamPosition());
        $this->assertIsInt($participantDto->getTimeCCingOthers());
        $this->assertIsInt($participantDto->getTimePlayed());
        $this->assertIsInt($participantDto->getTotalDamageDealt());
        $this->assertIsInt($participantDto->getTotalDamageDealtToChampions());
        $this->assertIsInt($participantDto->getTotalDamageShieldedOnTeammates());
        $this->assertIsInt($participantDto->getTotalDamageTaken());
        $this->assertIsInt($participantDto->getTotalHeal());
        $this->assertIsInt($participantDto->getTotalHealsOnTeammates());
        $this->assertIsInt($participantDto->getTotalMinionsKilled());
        $this->assertIsInt($participantDto->getTotalTimeCCDealt());
        $this->assertIsInt($participantDto->getTotalTimeSpentDead());
        $this->assertIsInt($participantDto->getTotalUnitsHealed());
        $this->assertIsInt($participantDto->getTripleKills());
        $this->assertIsInt($participantDto->getTrueDamageDealt());
        $this->assertIsInt($participantDto->getTrueDamageDealtToChampions());
        $this->assertIsInt($participantDto->getTrueDamageTaken());
        $this->assertIsInt($participantDto->getTurretKills());
        $this->assertIsInt($participantDto->getTurretTakedowns());
        $this->assertIsInt($participantDto->getTurretsLost());
        $this->assertIsInt($participantDto->getUnrealKills());
        $this->assertIsInt($participantDto->getVisionScore());
        $this->assertIsInt($participantDto->getVisionWardsBoughtInGame());
        $this->assertIsInt($participantDto->getWardsKilled());
        $this->assertIsInt($participantDto->getWardsPlaced());
        $this->assertIsBool($participantDto->isWin());
    }

    private function checkPerksDto(PerksDto $perksDto): void
    {
        $this->assertInstanceOf(PerkStatsDto::class, $perksDto->getStatPerks());
        $this->checkPerkStatsDto($perksDto->getStatPerks());

        if($perksDto->getStyles() > 0) {
            foreach ($perksDto->getStyles() as $style) {
                $this->assertInstanceOf(PerkStyleDto::class, $style);
            }
            $this->checkPerkStyleDto($perksDto->getStyles()[0]);
        }
    }

    private function checkPerkStyleDto(PerkStyleDto $perkStyleDto): void
    {
        $this->assertIsString($perkStyleDto->getDescription());
        $this->assertIsInt($perkStyleDto->getStyle());

        if($perkStyleDto->getSelections() > 0) {
            foreach ($perkStyleDto->getSelections() as $selection) {
                $this->assertInstanceOf(PerkStyleSelectionDto::class, $selection);
            }
            $this->checkPerkStyleSelectionDto($perkStyleDto->getSelections()[0]);
        }
    }

    private function checkPerkStyleSelectionDto(PerkStyleSelectionDto $perkStyleSelectionDto): void
    {
        $this->assertIsInt($perkStyleSelectionDto->getPerk());
        $this->assertIsInt($perkStyleSelectionDto->getVar1());
        $this->assertIsInt($perkStyleSelectionDto->getVar2());
        $this->assertIsInt($perkStyleSelectionDto->getVar3());
    }

    private function checkPerkStatsDto(PerkStatsDto $perkStatsDto): void
    {
        $this->assertisInt($perkStatsDto->getDefense());
        $this->assertisInt($perkStatsDto->getFlex());
        $this->assertisInt($perkStatsDto->getOffense());
    }

    private function checkTeamDto(TeamDto $teamDto): void
    {
        $this->assertIsInt($teamDto->getTeamId());
        $this->assertIsBool($teamDto->isWin());
        $this->assertInstanceOf(ObjectivesDto::class, $teamDto->getObjectives());
        $this->checkObjectivesDto($teamDto->getObjectives());

        if($teamDto->getBans() > 0) {
            foreach ($teamDto->getBans() as $ban) {
                $this->assertInstanceOf(BanDto::class, $ban);
            }
            $this->checkBanDto($teamDto->getBans()[0]);
        }
    }

    private function checkBanDto(BanDto $banDto): void
    {
        $this->assertIsInt($banDto->getChampionId());
        $this->assertIsInt($banDto->getPickTurn());
    }

    private function checkObjectivesDto(ObjectivesDto $objectivesDto): void
    {
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getRiftHerald());
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getBaron());
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getTower());
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getDragon());
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getInhibitor());
        $this->assertInstanceOf(ObjectiveDto::class, $objectivesDto->getChampion());
        $this->checkObjectiveDto($objectivesDto->getRiftHerald());
    }

    private function checkObjectiveDto(ObjectiveDto $objectiveDto): void
    {
        $this->assertIsInt($objectiveDto->getKills());
        $this->assertIsBool($objectiveDto->isFirst());
    }
}