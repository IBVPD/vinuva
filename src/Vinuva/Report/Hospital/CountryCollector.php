<?php

namespace Paho\Vinuva\Report\Hospital;

use Paho\Vinuva\Models\BaseDisease;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CountryCollector
{
    /** @var Country */
    private $country;

    /** @var Meningitis[] */
    private $meningitisCases;

    /** @var Pneumonia[] */
    private $pneumoniaCases;

    /** @var Rotavirus[] */
    private $rotavirusCases;

    public function __construct(Country $country)
    {
        $this->country         = $country;
        $this->meningitisCases = [];
        $this->pneumoniaCases  = [];
        $this->rotavirusCases  = [];
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function hasMeningitisCases(): bool
    {
        return !empty($this->meningitisCases);
    }

    public function hasPneumoniaCases(): bool
    {
        return !empty($this->pneumoniaCases);
    }

    public function hasRotavirusCases(): bool
    {
        return !empty($this->rotavirusCases);
    }

    public function getMeningitisCases(): array
    {
        return $this->meningitisCases;
    }

    public function getPneumoniaCases(): array
    {
        return $this->pneumoniaCases;
    }

    public function getRotavirusCases(): array
    {
        return $this->rotavirusCases;
    }

    public function getTotal(string $caseType, string $accessorStr): ?int
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $obj      = null;
        switch ($caseType) {
            case 'Meningitis':
                $obj = &$this->meningitisCases;
                break;
            case 'Pneumonia':
                $obj = &$this->pneumoniaCases;
                break;
            case 'Rotavirus':
                $obj = &$this->rotavirusCases;
                break;
        }

        if (empty($obj)) {
            return null;
        }

        $value = 0;
        foreach ($obj as $case) {
            $ret = $accessor->getValue($case, $accessorStr);
            if ($ret > 0) {
                $value += $ret;
            }
        }

        return $value;
    }

    public function addCase(BaseDisease $case): void
    {
        switch (get_class($case)) {
            case Rotavirus::class:
                $this->rotavirusCases[$case->getHospital()->getId()] = $case;
                break;
            case Pneumonia::class:
                $this->pneumoniaCases[$case->getHospital()->getId()] = $case;
                break;
            case Meningitis::class:
                $this->meningitisCases[$case->getHospital()->getId()] = $case;
                break;
        }
    }
}
