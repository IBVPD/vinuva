<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models\Common;

use Serializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class DeathCount implements Serializable
{
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $_12;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $_23;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $_59;

    /**
     * @var int|null
     * @ORM\Column(name="_total", type="integer", nullable=true)
     */
    private $total;

    public function __construct(?int $_12, ?int $_23, ?int $_59, ?int $total)
    {
        $this->_12   = $_12;
        $this->_23   = $_23;
        $this->_59   = $_59;
        $this->total = $total;
    }

    public function get12(): ?int
    {
        return $this->_12;
    }

    public function get23(): ?int
    {
        return $this->_23;
    }

    public function get59(): ?int
    {
        return $this->_59;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function serialize(): string
    {
        return serialize([
            $this->_12,
            $this->_23,
            $this->_59,
            $this->total
        ]);
    }

    public function unserialize($serialized): void
    {
        [
            $this->_12,
            $this->_23,
            $this->_59,
            $this->total
        ] = unserialize($serialized, [__CLASS__]);
    }


}
