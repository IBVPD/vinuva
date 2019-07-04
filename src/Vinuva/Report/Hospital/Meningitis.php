<?php

namespace Paho\Vinuva\Report\Hospital;

use Paho\Vinuva\Models\Common\Confirmed;
use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Common\DeathCount;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Meningitis as BaseCase;

class Meningitis extends BaseCase
{
    public function __construct(Hospital $hospital, ?int $under5, ?int $suspected, ?int $suspectedWith, ?Probable $probable, ?Confirmed $u12Confirmed, ?Confirmed $u23Confirmed, ?Confirmed $u59Confirmed, ?Confirmed $confirmed, ?DeathCount $deathCount)
    {
        parent::__construct($hospital, 0, 0);
        $this->under5           = $under5;
        $this->suspected        = $suspected;
        $this->suspectedWith    = $suspectedWith;
        $this->probable         = $probable;
        $this->under12Confirmed = $u12Confirmed;
        $this->under23Confirmed = $u23Confirmed;
        $this->under59Confirmed = $u59Confirmed;
        $this->totalConfirmed   = $confirmed;
        $this->numberOfDeaths   = $deathCount;
    }
}
