<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\ORM\Mapping as ORM;
use Paho\Vinuva\Models\Common\DeathCount;

/**
 * @ORM\Entity
 * @ORM\Table(name="pneumonia",uniqueConstraints={@ORM\UniqueConstraint(name="p_country_hospital_year_month_idx",columns={"country_id","hospital_id","year","month"})})
 */
class Pneumonia extends BaseDisease
{
    /**
     * @var int|null
     * @ORM\Column(name="suspected",type="integer",nullable=true)
     */
    private $suspected;

    /**
     * @var int|null
     * @ORM\Column(name="suspectedWith",type="integer",nullable=true)
     */
    private $suspectedWith;

    /**
     * @var Common\Probable|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $probable;

    /**
     * @var Common\Probable|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $probableWithBlood;

    /**
     * @var Common\Probable|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $probableWithPleural;

    /**
     * @var Common\Confirmed|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $under12Confirmed;

    /**
     * @var Common\Confirmed|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $under23Confirmed;

    /**
     * @var Common\Confirmed|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $under59Confirmed;

    /**
     * @var Common\Confirmed|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $totalConfirmed;

    /**
     * @var Common\DeathCount|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $numberOfDeaths;

    public function getSuspected(): ?int
    {
        return $this->suspected;
    }

    public function setSuspected(?int $suspected): void
    {
        $this->suspected = $suspected;
    }

    public function getSuspectedWith(): ?int
    {
        return $this->suspectedWith;
    }

    public function setSuspectedWith(?int $suspectedWith): void
    {
        $this->suspectedWith = $suspectedWith;
    }

    public function getProbable(): ?Common\Probable
    {
        return $this->probable;
    }

    public function setProbable(?Common\Probable $probable): void
    {
        $this->probable = $probable;
    }

    public function getProbableWithBlood(): ?Common\Probable
    {
        return $this->probableWithBlood;
    }

    public function setProbableWithBlood(?Common\Probable $probableWithBlood): void
    {
        $this->probableWithBlood = $probableWithBlood;
    }

    public function getProbableWithPleural(): ?Common\Probable
    {
        return $this->probableWithPleural;
    }

    public function setProbableWithPleural(?Common\Probable $probableWithPleural): void
    {
        $this->probableWithPleural = $probableWithPleural;
    }

    public function getUnder12Confirmed(): ?Common\Confirmed
    {
        return $this->under12Confirmed;
    }

    public function setUnder12Confirmed(?Common\Confirmed $under12Confirmed): void
    {
        $this->under12Confirmed = $under12Confirmed;
    }

    public function getUnder23Confirmed(): ?Common\Confirmed
    {
        return $this->under23Confirmed;
    }

    public function setUnder23Confirmed(?Common\Confirmed $under23Confirmed): void
    {
        $this->under23Confirmed = $under23Confirmed;
    }

    public function getUnder59Confirmed(): ?Common\Confirmed
    {
        return $this->under59Confirmed;
    }

    public function setUnder59Confirmed(?Common\Confirmed $under59Confirmed): void
    {
        $this->under59Confirmed = $under59Confirmed;
    }

    public function getTotalConfirmed(): ?Common\Confirmed
    {
        return $this->totalConfirmed;
    }

    public function setTotalConfirmed(?Common\Confirmed $totalConfirmed): void
    {
        $this->totalConfirmed = $totalConfirmed;
    }

    public function getNumberOfDeaths(): ?DeathCount
    {
        return $this->numberOfDeaths;
    }

    public function setNumberOfDeaths(?DeathCount $numberOfDeaths): void
    {
        $this->numberOfDeaths = $numberOfDeaths;
    }
}
