<?php

namespace App\Match\Domain\Repository;

use App\Match\Domain\Model\Participant;

interface ParticipantRepositoryInterface
{
    public function findById(string $participantId): ?Participant;
}
