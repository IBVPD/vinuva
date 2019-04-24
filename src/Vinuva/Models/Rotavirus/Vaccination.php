<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models\Rotavirus;

use \Serializable;

class Vaccination implements Serializable
{
    /** @var int|null */
    private $vaccinated;

    /** @var int|null */
    private $notVaccinated;

    /** @var int|null */
    private $noInformation;

    public function __construct(?int $vaccinated, ?int $notVaccinated, ?int $noInformation)
    {
        $this->vaccinated    = $vaccinated;
        $this->notVaccinated = $notVaccinated;
        $this->noInformation = $noInformation;
    }

    public function getVaccinated(): ?int
    {
        return $this->vaccinated;
    }

    public function getNotVaccinated(): ?int
    {
        return $this->notVaccinated;
    }

    public function getNoInformation(): ?int
    {
        return $this->noInformation;
    }

    public function serialize(): string
    {
        return serialize([$this->vaccinated, $this->notVaccinated, $this->noInformation]);
    }

    public function unserialize($serialized): void
    {
        [$this->vaccinated, $this->notVaccinated, $this->noInformation] = unserialize($serialized, [__CLASS__]);
    }
}
