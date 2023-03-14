<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CodeTrait
{
    #[ORM\Column(length: 255, unique: true)]
    private ?string $code = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}