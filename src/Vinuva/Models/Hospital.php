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
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    public function __construct(string $name, Country $country)
    {
        $this->name    = $name;
        $this->country = $country;
    }

    public function __toString()
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

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
}
