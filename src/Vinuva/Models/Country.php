<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="countries")
 */
class Country
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=64)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity="Region")
     */
    private $region;

    public function __construct(string $id, string $name, Region $region)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->region = $region;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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
}
