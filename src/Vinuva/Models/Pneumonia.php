<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\ORM\Mapping as ORM;
use Paho\Vinuva\Models\Common\DeathCount;
use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Common\Confirmed;
use Symfony\Component\Validator\Constraints as Assert;
use Paho\Vinuva\Validator as LocalAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pneumonia",uniqueConstraints={@ORM\UniqueConstraint(name="p_country_hospital_year_month_idx",columns={"country_id","hospital_id","year","month"})})
 */
class Pneumonia extends BaseDisease
{
    /**
     * @var int|null
     * @ORM\Column(name="suspected",type="integer",nullable=true)
     * @Assert\LessThanOrEqual(propertyPath="under5", message="Must be less than number of hospitalizations")
     */
    protected $suspected;

    /**
     * @var int|null
     * @ORM\Column(name="suspectedWith",type="integer",nullable=true)
     * @Assert\LessThanOrEqual(propertyPath="suspected", message="Must be less than or equal to the number of suspected cases")
     */
    protected $suspectedWith;

    /**
     * @var Probable|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Probable", columnPrefix="probable")
     * @LocalAssert\LessThanOrEqualProbable(propertyPath="suspected", message="Must be less than or equal to the number of suspected cases")
     */
    protected $probable;

    /**
     * @var Probable|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Probable", columnPrefix="probable_with_blood")
     * @LocalAssert\LessThanOrEqualProbable(propertyPath="suspected", message="Must be less than or equal to the number of suspected cases")
     */
    protected $probableWithBlood;

    /**
     * @var Probable|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Probable", columnPrefix="probable_with_pleural")
     * @LocalAssert\LessThanOrEqualProbable(propertyPath="suspected", message="Must be less than or equal to the number of suspected cases")
     */
    protected $probableWithPleural;

    /**
     * @var Confirmed|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Confirmed", columnPrefix="u12_confirmed_")
     */
    protected $under12Confirmed;

    /**
     * @var Confirmed|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Confirmed", columnPrefix="u23_confirmed_")
     */
    protected $under23Confirmed;

    /**
     * @var Confirmed|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Confirmed", columnPrefix="u59_confirmed_")
     */
    protected $under59Confirmed;

    /**
     * @var Confirmed|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\Confirmed", columnPrefix="total_confirmed_")
     * @LocalAssert\LessThanOrEqualConfirmed(propertyPath="suspected", message="The sum of all confirmed cases must be less than or equal to the number of suspected cases")
     */
    protected $totalConfirmed;

    /**
     * @var Common\DeathCount|null
     * @ORM\Embedded(class="Paho\Vinuva\Models\Common\DeathCount", columnPrefix="number_of_deaths")
     */
    protected $numberOfDeaths;

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
