<?php

namespace Paho\Vinuva\Report;

use Paho\Vinuva\Report\Common\Probable;
use Paho\Vinuva\Report\Common\Confirmed;
use Paho\Vinuva\Report\Common\DeathCount;

class MeningitisSummary
{
    /** @var string */
    private $country;

    /** @var int */
    private $year;

    /** @var array|null */
    private $under5;

    /** @var array|null */
    private $suspected;

    /** @var array|null */
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

    public function __construct(string $country, int $year, ?string $under5, ?string $suspected, ?string $suspectedWith, Probable $probable, Confirmed $under12Confirmed, Confirmed $under23Confirmed, Confirmed $under59Confirmed, Confirmed $totalConfirmed, DeathCount $deathCount)
    {
        $this->country          = $country;
        $this->year             = $year;
        $this->under5           = $under5 ? GroupConcatExploder::explodeMonths($under5) : GroupConcatExploder::$defaultMonths;
        $this->suspected        = $suspected ? GroupConcatExploder::explodeMonths($suspected) : GroupConcatExploder::$defaultMonths;
        $this->suspectedWith    = $suspectedWith ? GroupConcatExploder::explodeMonths($suspectedWith) : GroupConcatExploder::$defaultMonths;
        $this->probable         = $probable;
        $this->under12Confirmed = $under12Confirmed;
        $this->under23Confirmed = $under23Confirmed;
        $this->under59Confirmed = $under59Confirmed;
        $this->totalConfirmed = $totalConfirmed;
        $this->numberOfDeaths = $deathCount;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getUnder5($month): ?int
    {
        return $this->under5[$month];
    }

    public function getProbable(): ?Probable
    {
        return $this->probable;
    }

    public function getSuspected($month): ?int
    {
        return $this->suspected[$month];
    }

    public function getSuspectedWith($month): ?int
    {
        return $this->suspectedWith[$month];
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
