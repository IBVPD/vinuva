<?php declare(strict_types=1);

namespace Paho\Vinuva\Report;

use Paho\Vinuva\Report\Common\Probable;
use Paho\Vinuva\Report\Rotavirus\Vaccination;

class RotavirusSummary
{
    /** @var string */
    private $country;

    /** @var int */
    private $year;

    /** @var array|null */
    private $under5;

    /** @var array|null */
    private $under5With;

    /** @var array|null */
    private $suspected;

    /** @var Probable|null */
    private $probable;

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

    public function __construct(string $country, int $year, ?string $under5, ?string $under5With, ?string $suspected, Probable $withFormAndSample, Vaccination $positiveUnder12, Vaccination $positiveUnder23, Vaccination $positiveUnder59, Vaccination $positiveTotal, Vaccination $deathsUnder12, Vaccination $deathsUnder23, Vaccination $deathsUnder59, Vaccination $deathsTotal)
    {
        $this->country         = $country;
        $this->year            = $year;
        $this->under5          = $under5 ? GroupConcatExploder::explodeMonths($under5) : GroupConcatExploder::$defaultMonths;
        $this->under5With      = $under5With ? GroupConcatExploder::explodeMonths($under5With) : GroupConcatExploder::$defaultMonths;
        $this->suspected       = $suspected ? GroupConcatExploder::explodeMonths($suspected) : GroupConcatExploder::$defaultMonths;
        $this->probable        = $withFormAndSample;
        $this->positiveUnder12 = $positiveUnder12;
        $this->positiveUnder23 = $positiveUnder23;
        $this->positiveUnder59 = $positiveUnder59;
        $this->positiveTotal   = $positiveTotal;
        $this->deathsUnder12   = $deathsUnder12;
        $this->deathsUnder23   = $deathsUnder23;
        $this->deathsUnder59   = $deathsUnder59;
        $this->deathsTotal     = $deathsTotal;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getUnder5($month)
    {
        return $this->under5[$month];
    }

    public function getUnder5With($month)
    {
        return $this->under5With[$month];
    }

    public function getProbable(): ?Probable
    {
        return $this->probable;
    }

    public function getSuspected($month)
    {
        return $this->suspected[$month];
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
