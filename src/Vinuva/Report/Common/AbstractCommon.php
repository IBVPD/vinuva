<?php

namespace Paho\Vinuva\Report\Common;

use Paho\Vinuva\Report\GroupConcatExploder;

class AbstractCommon
{
    /** @var array */
    protected $_12;

    /** @var array */
    protected $_23;

    /** @var array */
    protected $_59;

    /** @var array */
    protected $total;

    public function __construct(?string $_12, ?string $_23, ?string $_59, ?string $total)
    {
        $this->_12   = $_12 ? GroupConcatExploder::explodeMonths($_12) : GroupConcatExploder::$defaultMonths;
        $this->_23   = $_23 ? GroupConcatExploder::explodeMonths($_23) : GroupConcatExploder::$defaultMonths;
        $this->_59   = $_59 ? GroupConcatExploder::explodeMonths($_59) : GroupConcatExploder::$defaultMonths;
        $this->total = $total ? GroupConcatExploder::explodeMonths($total) : GroupConcatExploder::$defaultMonths;
    }

    /**
     * @param int|string $key
     * @param int        $month
     *
     * @return string|null
     */
    public function get($key, $month): ?string
    {
        switch ($key) {
            case 12:
                return $this->_12[$month];
            case 23:
                return $this->_23[$month];
            case 59:
                return $this->_59[$month];
            case 'total':
                return (string)$this->total[$month];
        }

        return null;
    }

    public function get12($month): ?string
    {
        return $this->_12[$month];
    }

    public function get23($month): ?string
    {
        return $this->_23[$month];
    }

    public function get59($month): ?string
    {
        return $this->_59[$month];
    }

    public function getTotal($month): ?string
    {
        return $this->total[$month];
    }
}
