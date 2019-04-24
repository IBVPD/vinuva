<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\ORM\Mapping as ORM;
use Paho\Vinuva\Models\Rotavirus\Vaccination;

/**
 * @ORM\Entity
 * @ORM\Table(name="rotavirus",uniqueConstraints={@ORM\UniqueConstraint(name="p_country_hospital_year_month_idx",columns={"country_id","hospital_id","year","month"})})
 */
class Rotavirus extends BaseDisease
{
    /**
     * @var int|null
     * @ORM\Column(name="under5With",type="integer",nullable=true)
     */
    private $under5With;

    /**
     * @var int|null
     * @ORM\Column(name="suspected",type="integer",nullable=true)
     */
    private $suspected;

    /**
     * @var Common\Probable|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $withFormAndSample;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $positiveUnder12;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $positiveUnder23;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $positiveUnder59;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $positiveTotal;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $deathsUnder12;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $deathsUnder23;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $deathsUnder59;

    /**
     * @var Rotavirus\Vaccination|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $deathsTotal;

    public function getUnder5With(): ?int
    {
        return $this->under5With;
    }

    public function setUnder5With(?int $under5With): void
    {
        $this->under5With = $under5With;
    }

    public function getSuspected(): ?int
    {
        return $this->suspected;
    }

    public function setSuspected(?int $suspected): void
    {
        $this->suspected = $suspected;
    }

    public function getWithFormAndSample(): ?Common\Probable
    {
        return $this->withFormAndSample;
    }

    public function setWithFormAndSample(?Common\Probable $withFormAndSample): void
    {
        $this->withFormAndSample = $withFormAndSample;
    }

    public function getPositiveUnder12(): ?Vaccination
    {
        return $this->positiveUnder12;
    }

    public function setPositiveUnder12(?Vaccination $positiveUnder12): void
    {
        $this->positiveUnder12 = $positiveUnder12;
    }

    public function getPositiveUnder23(): ?Vaccination
    {
        return $this->positiveUnder23;
    }

    public function setPositiveUnder23(?Vaccination $positiveUnder23): void
    {
        $this->positiveUnder23 = $positiveUnder23;
    }

    public function getPositiveUnder59(): ?Vaccination
    {
        return $this->positiveUnder59;
    }

    public function setPositiveUnder59(?Vaccination $positiveUnder59): void
    {
        $this->positiveUnder59 = $positiveUnder59;
    }

    public function getPositiveTotal(): ?Vaccination
    {
        return $this->positiveTotal;
    }

    public function setPositiveTotal(?Vaccination $positiveTotal): void
    {
        $this->positiveTotal = $positiveTotal;
    }

    public function getDeathsUnder12(): ?Vaccination
    {
        return $this->deathsUnder12;
    }

    public function setDeathsUnder12(?Vaccination $deathsUnder12): void
    {
        $this->deathsUnder12 = $deathsUnder12;
    }

    public function getDeathsUnder23(): ?Vaccination
    {
        return $this->deathsUnder23;
    }

    public function setDeathsUnder23(?Vaccination $deathsUnder23): void
    {
        $this->deathsUnder23 = $deathsUnder23;
    }

    public function getDeathsUnder59(): ?Vaccination
    {
        return $this->deathsUnder59;
    }

    public function setDeathsUnder59(?Vaccination $deathsUnder59): void
    {
        $this->deathsUnder59 = $deathsUnder59;
    }

    public function getDeathsTotal(): ?Vaccination
    {
        return $this->deathsTotal;
    }

    public function setDeathsTotal(?Vaccination $deathsTotal): void
    {
        $this->deathsTotal = $deathsTotal;
    }

}
