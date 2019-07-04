<?php

namespace Paho\Vinuva\Report\Country;

use Paho\Vinuva\Models\Common\Confirmed;
use Paho\Vinuva\Models\Common\DeathCount;
use Paho\Vinuva\Models\Common\Probable;

class MeningitisSummary
{
    /** @var int */
    private $countryId;

    /** @var string */
    private $country;

    /** @var int|null */
    private $under5;

    /** @var int|null */
    private $suspected;

    /** @var int|null */
    private $suspectedWith;

    /** @var Probable|null */
    private $probable;

    /** @var Confirmed|null */
    private $under12Confirmed;

    /** @var Confirmed|null */
    private $under23Confirmed;

    /** @var Confirmed|null */
    private $under59Confirmed;

    /** @var Confirmed|null */
    private $totalConfirmed;

    /** @var DeathCount|null */
    private $numberOfDeaths;

    public function __construct(int $countryId, string $country, ?int $under5, ?int $suspected, ?int $suspectedWith, ?Probable $probable, ?Confirmed $under12Confirmed, ?Confirmed $under23Confirmed, ?Confirmed $under59Confirmed, ?Confirmed $totalConfirmed, ?DeathCount $numberOfDeaths)
    {
        $this->countryId        = $countryId;
        $this->country          = $country;
        $this->under5           = $under5;
        $this->suspected        = $suspected;
        $this->suspectedWith    = $suspectedWith;
        $this->probable         = $probable;
        $this->under12Confirmed = $under12Confirmed;
        $this->under23Confirmed = $under23Confirmed;
        $this->under59Confirmed = $under59Confirmed;
        $this->totalConfirmed   = $totalConfirmed;
        $this->numberOfDeaths   = $numberOfDeaths;
    }

    public function getCountryId(): int
    {
        return $this->countryId;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getUnder5(): ?int
    {
        return $this->under5;
    }

    public function getSuspected(): ?int
    {
        return $this->suspected;
    }

    public function getSuspectedWith(): ?int
    {
        return $this->suspectedWith;
    }

    public function getProbable(): ?Probable
    {
        return $this->probable;
    }

    public function getUnder12Confirmed(): ?Confirmed
    {
        return $this->under12Confirmed;
    }

    public function getUnder23Confirmed(): ?Confirmed
    {
        return $this->under23Confirmed;
    }

    public function getUnder59Confirmed(): ?Confirmed
    {
        return $this->under59Confirmed;
    }

    public function getTotalConfirmed(): ?Confirmed
    {
        return $this->totalConfirmed;
    }

    public function getNumberOfDeaths(): ?DeathCount
    {
        return $this->numberOfDeaths;
    }
}
