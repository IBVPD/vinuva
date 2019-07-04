<?php

namespace Paho\Vinuva\Report\Country;

use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Rotavirus\Vaccination;

class RotavirusSummary
{
    /** @var int */
    private $countryId;

    /** @var string */
    private $country;

    /** @var int|null */
    private $under5;

    /** @var int|null */
    private $under5With;

    /** @var int|null */
    private $suspected;

    /** @var Probable|null */
    private $withFormAndSample;

    /** @var Vaccination|null */
    private $positiveUnder12;

    /** @var Vaccination|null */
    private $positiveUnder23;

    /** @var Vaccination|null */
    private $positiveUnder59;

    /** @var Vaccination|null */
    private $positiveTotal;

    /** @var Vaccination|null */
    private $deathsUnder12;

    /** @var Vaccination|null */
    private $deathsUnder23;

    /** @var Vaccination|null */
    private $deathsUnder59;

    /** @var Vaccination|null */
    private $deathsTotal;

    public function __construct(int $countryId, string $country, ?int $under5, ?int $under5With, ?int $suspected, ?Probable $withFormAndSample, ?Vaccination $positiveUnder12, ?Vaccination $positiveUnder23, ?Vaccination $positiveUnder59, ?Vaccination $positiveTotal, ?Vaccination $deathsUnder12, ?Vaccination $deathsUnder23, ?Vaccination $deathsUnder59, ?Vaccination $deathsTotal)
    {
        $this->countryId         = $countryId;
        $this->country           = $country;
        $this->under5            = $under5;
        $this->under5With        = $under5With;
        $this->suspected         = $suspected;
        $this->withFormAndSample = $withFormAndSample;
        $this->positiveUnder12   = $positiveUnder12;
        $this->positiveUnder23   = $positiveUnder23;
        $this->positiveUnder59   = $positiveUnder59;
        $this->positiveTotal     = $positiveTotal;
        $this->deathsUnder12     = $deathsUnder12;
        $this->deathsUnder23     = $deathsUnder23;
        $this->deathsUnder59     = $deathsUnder59;
        $this->deathsTotal       = $deathsTotal;
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

    public function getUnder5With(): ?int
    {
        return $this->under5With;
    }

    public function getSuspected(): ?int
    {
        return $this->suspected;
    }

    public function getWithFormAndSample(): ?Probable
    {
        return $this->withFormAndSample;
    }

    public function getPositiveUnder12(): ?Vaccination
    {
        return $this->positiveUnder12;
    }

    public function getPositiveUnder23(): ?Vaccination
    {
        return $this->positiveUnder23;
    }

    public function getPositiveUnder59(): ?Vaccination
    {
        return $this->positiveUnder59;
    }

    public function getPositiveTotal(): ?Vaccination
    {
        return $this->positiveTotal;
    }

    public function getDeathsUnder12(): ?Vaccination
    {
        return $this->deathsUnder12;
    }

    public function getDeathsUnder23(): ?Vaccination
    {
        return $this->deathsUnder23;
    }

    public function getDeathsUnder59(): ?Vaccination
    {
        return $this->deathsUnder59;
    }

    public function getDeathsTotal(): ?Vaccination
    {
        return $this->deathsTotal;
    }
}
