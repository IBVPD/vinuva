<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Confirmed //implements \Serializable
{
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hib;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hi;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nm;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spn;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $other;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contamination;

    public function __construct(?int $hib, ?int $hi, ?int $nm, ?int $spn, ?int $other, ?int $contamination)
    {
        $this->hib           = $hib;
        $this->hi            = $hi;
        $this->nm            = $nm;
        $this->spn           = $spn;
        $this->other         = $other;
        $this->contamination = $contamination;
    }

    public function getHib(): ?int
    {
        return $this->hib;
    }

    public function getHi(): ?int
    {
        return $this->hi;
    }

    public function getNm(): ?int
    {
        return $this->nm;
    }

    public function getSpn(): ?int
    {
        return $this->spn;
    }

    public function getOther(): ?int
    {
        return $this->other;
    }

    public function getContamination(): ?int
    {
        return $this->contamination;
    }

    public function serialize(): string
    {
        return serialize([
            $this->hib,
            $this->hi,
            $this->nm,
            $this->spn,
            $this->other,
            $this->contamination
        ]);
    }

    public function unserialize($serialized): void
    {
        [
            $this->hib,
            $this->hi,
            $this->nm,
            $this->spn,
            $this->other,
            $this->contamination
        ] = unserialize($serialized, [__CLASS__]);
    }
}
