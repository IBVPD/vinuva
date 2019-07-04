<?php

namespace Paho\Vinuva\Report\Hospital;

use Paho\Vinuva\Models\Common\Probable;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Region;
use Paho\Vinuva\Models\Rotavirus as BaseCase;

class Rotavirus extends BaseCase
{
    public function __construct(
        Hospital $hospital,
        ?int $under5,
        ?int $under5With,
        ?int $suspected,
        Probable $probable,
        BaseCase\Vaccination $u12,
        BaseCase\Vaccination $u23,
        BaseCase\Vaccination $u59,
        BaseCase\Vaccination $uTotal,
        BaseCase\Vaccination $d12,
        BaseCase\Vaccination $d23,
        BaseCase\Vaccination $d59,
        BaseCase\Vaccination $dTotal
    )
    {
//        $hospitalObj = new Hospital($hospital, new Country('x', 'n/a', new Region('Unknown')));
        parent::__construct($hospital, 0, 0);
        $this->under5            = $under5;
        $this->under5With        = $under5With;
        $this->suspected         = $suspected;
        $this->withFormAndSample = $probable;
        $this->positiveUnder12   = $u12;
        $this->positiveUnder23   = $u23;
        $this->positiveUnder59   = $u59;
        $this->positiveTotal     = $uTotal;
        $this->deathsUnder12     = $d12;
        $this->deathsUnder23     = $d23;
        $this->deathsUnder59     = $d59;
        $this->deathsTotal       = $dTotal;
    }
}
