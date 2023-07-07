<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class MatchDto
{
    private MetadataDto $metadata;
    private InfoDto $info;

    public function getMetadata(): MetadataDto
    {
        return $this->metadata;
    }

    public function setMetadata(MetadataDto $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getInfo(): InfoDto
    {
        return $this->info;
    }

    public function setInfo(InfoDto $info): self
    {
        $this->info = $info;

        return $this;
    }
}
