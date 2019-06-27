<?php declare(strict_types=1);

namespace Paho\Vinuva\Report\Common;

use Paho\Vinuva\Report\GroupConcatExploder;

class Confirmed
{
    /** @var array */
    private $hib;

    /** @var array */
    private $hi;

    /** @var array */
    private $nm;

    /** @var array */
    private $spn;

    /** @var array */
    private $other;

    /** @var array */
    private $contamination;

    /** @var array */
    private $total;

    public function __construct(?string $hib, ?string $hi, ?string $nm, ?string $spn, ?string $other, ?string $contamination, ?string $total = null)
    {
        $this->hib           = $hib ? GroupConcatExploder::explodeMonths($hib) : GroupConcatExploder::$defaultMonths;
        $this->hi            = $hi ? GroupConcatExploder::explodeMonths($hi) : GroupConcatExploder::$defaultMonths;
        $this->nm            = $nm ? GroupConcatExploder::explodeMonths($nm) : GroupConcatExploder::$defaultMonths;
        $this->spn           = $spn ? GroupConcatExploder::explodeMonths($spn) : GroupConcatExploder::$defaultMonths;
        $this->other         = $other ? GroupConcatExploder::explodeMonths($other) : GroupConcatExploder::$defaultMonths;
        $this->contamination = $contamination ? GroupConcatExploder::explodeMonths($contamination) : GroupConcatExploder::$defaultMonths;
        $this->total         = $total ? GroupConcatExploder::explodeMonths($total) : GroupConcatExploder::$defaultMonths;
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
            case 'hib':
                return $this->hib[$month];
            case 'hi':
                return $this->hi[$month];
            case 'nm':
                return $this->nm[$month];
            case 'spn':
                return $this->spn[$month];
            case 'other':
                return $this->other[$month];
            case 'contaminant':
                return $this->contamination[$month];
            case 'total':
                return $this->total[$month];
        }

        return null;
    }

    public function getHib(int $month): ?string
    {
        return $this->hib[$month];
    }

    public function getHi(int $month): ?string
    {
        return $this->hi[$month];
    }

    public function getNm(int $month): ?string
    {
        return $this->nm[$month];
    }

    public function getSpn(int $month): ?string
    {
        return $this->spn[$month];
    }

    public function getOther(int $month): ?string
    {
        return $this->other[$month];
    }

    public function getContamination(int $month): ?string
    {
        return $this->contamination[$month];
    }

    public function getTotal(int $month): ?string
    {
        return $this->total[$month];
    }
}
