<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class MatchDto
{
    private MetadataDto $metadata;
    private InfoDto $info;

    /**
     * @return MetadataDto
     */
    public function getMetadata(): MetadataDto
    {
        return $this->metadata;
    }

    /**
     * @param MetadataDto $metadata
     * @return MatchDto
     */
    public function setMetadata(MetadataDto $metadata): MatchDto
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return InfoDto
     */
    public function getInfo(): InfoDto
    {
        return $this->info;
    }

    /**
     * @param InfoDto $info
     * @return MatchDto
     */
    public function setInfo(InfoDto $info): MatchDto
    {
        $this->info = $info;
        return $this;
    }
}