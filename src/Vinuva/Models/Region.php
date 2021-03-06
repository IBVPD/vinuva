<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="regions")
 */
class Region
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
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;

    /**
     * @var Collection|Country[]
     * @ORM\OneToMany(targetEntity="Country",mappedBy="region")
     */
    private $countries;

    public function __construct(string $name)
    {
        $this->name      = $name;
        $this->countries = new ArrayCollection();
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

    public function getCountries(): Collection
    {
        return $this->countries;
    }
}
