<?php

namespace Paho\Vinuva\Report;

use InvalidArgumentException;
use Paho\Vinuva\Models\BaseDisease;

class CaseVerification
{
    /** @var int */
    private $country;
    /** @var int */
    private $hospital;
    /** @var string */
    private $disease;
    /** @var int */
    private $year;
    /** @var array */
    private $months = [1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null, 8 => null, 9 => null, 10 => null, 11 => null, 12 => null];

    public function __construct(string $country, string $hospital, string $disease, int $year, string $months)
    {
        $this->country  = $country;
        $this->hospital = $hospital;
        $this->disease  = $disease;
        $this->year     = $year;
        $explodedMonths = explode(',', $months);
        foreach ($explodedMonths as $value) {
            [$month, $verifiedStr] = explode('=', $value);
            $this->months[$month] = $verifiedStr;
        }
//        $this->months   = explode(',', $months);//;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getHospital(): string
    {
        return $this->hospital;
    }

    public function getDisease(): string
    {
        return $this->disease;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function verified(int $month): ?string
    {
        return $this->months[$month];
    }

    public function addCase(BaseDisease $case): void
    {
        if ($case->getYear() !== $this->year) {
            throw new InvalidArgumentException('Mismatched year');
        }

        $this->months[$case->getMonth()] = $case->isVerified();
    }
}
