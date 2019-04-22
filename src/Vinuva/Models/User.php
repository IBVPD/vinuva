<?php

namespace Paho\Vinuva\Models;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements UserInterface
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
     * @ORM\Column(name="name",type="string", length=128)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="email",type="string", length=190)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(name="password",type="string")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="salt",type="string")
     */
    private $salt;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    /**
     * @var Hospital|null
     * @ORM\ManyToOne(targetEntity="Hospital")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hospital;

    public function __construct(string $name, string $email, Country $country)
    {
        $this->name    = $name;
        $this->email   = $email;
        $this->country = $country;
        $this->salt    = sha256(sprintf('{%s}(%s)-%s',$name,$email,uniqid('vinuva-user', true)));
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getHospital(): ?Hospital
    {
        return $this->hospital;
    }

    public function setHospital(?Hospital $hospital): void
    {
        $this->hospital = $hospital;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {

    }
}
