<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="hospitals")
 */
class Hospital
{
    /**
     * @var int|null
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name",type="string",length=128)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="short",type="string",length=128)
     */
    private $short;

    /**
     * @var string
     * @ORM\Column(name="local",type="string",length=128)
     */
    private $local;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="hospitals")
     */
    private $country;

    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    public static function createDTO(int $id, string $name): Hospital
    {
        $obj     = new self($name, new Country('N/A', 'N/A', new Region('N/A')), 'short', 'local');
        $obj->id = $id;

        return $obj;
    }

    public function __construct(string $name, Country $country, string $short, string $local)
    {
        $this->name    = $name;
        $this->country = $country;
        $this->short   = $short;
        $this->local   = $local;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getShort(bool $fallback = false): string
    {
        if (!$this->short && $fallback) {
            return $this->name;
        }

        return $this->short;
    }

    public function setShort(string $short): void
    {
        $this->short = $short;
    }

    public function getLocal(bool $fallback = false): string
    {
        if (!$this->local && $fallback) {
            return $this->name;
        }

        return $this->local;
    }

    public function setLocal(string $local): void
    {
        $this->local = $local;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
