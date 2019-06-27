<?php declare(strict_types=1);

namespace Paho\Vinuva\Report\Rotavirus;

use Paho\Vinuva\Report\GroupConcatExploder;

class Vaccination
{
    /** @var array|null  */
    private $vaccinated;

    /** @var array|null  */
    private $notVaccinated;

    /** @var array|null  */
    private $noInformation;

    public function __construct(?string $vaccinated, ?string $notVaccinated, ?string $noInformation)
    {
        $this->vaccinated    = $vaccinated ? GroupConcatExploder::explodeMonths($vaccinated): GroupConcatExploder::$defaultMonths;
        $this->notVaccinated = $notVaccinated ? GroupConcatExploder::explodeMonths($notVaccinated): GroupConcatExploder::$defaultMonths;
        $this->noInformation = $noInformation ? GroupConcatExploder::explodeMonths($noInformation): GroupConcatExploder::$defaultMonths;
    }

    /**
     * @param string     $key
     * @param int|string $month
     *
     * @return string|int|null
     */
    public function get(string $key, $month)
    {
        switch ($key) {
            case 'vaccinated':
                return $this->vaccinated[$month];
            case 'notVaccinated':
                return $this->notVaccinated[$month];
            case 'noInformation':
                return $this->noInformation[$month];
        }

        return null;
    }

    public function getVaccinated($month): ?string
    {
        return $this->vaccinated[$month];
    }

    public function getNotVaccinated($month): ?string
    {
        return $this->notVaccinated[$month];
    }

    public function getNoInformation($month): ?string
    {
        return $this->noInformation[$month];
    }
}
