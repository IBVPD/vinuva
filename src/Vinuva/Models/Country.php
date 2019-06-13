<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="countries")
 */
class Country
{
    /**
     * @var int|null
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=32)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(name="iso2", type="string", nullable=true, length=64)
     */
    private $iso2;

    /**
     * @var string|null
     * @ORM\Column(name="fips", type="string", nullable=true, length=64)
     */
    private $fips;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="Region",inversedBy="countries")
     */
    private $region;

    /**
     * @var Collection|Hospital[]
     * @ORM\OneToMany(targetEntity="Hospital", mappedBy="country")
     */
    private $hospitals;

    public function __construct(string $code, string $name, Region $region)
    {
        $this->code      = $code;
        $this->name      = $name;
        $this->region    = $region;
        $this->hospitals = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(?string $iso2): void
    {
        $this->iso2 = $iso2;
    }

    public function getFips(): ?string
    {
        return $this->fips;
    }

    public function setFips(?string $fips): void
    {
        $this->fips = $fips;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    public function getHospitals(): Collection
    {
        return $this->hospitals;
    }
}
