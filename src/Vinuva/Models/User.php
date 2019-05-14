<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    public const
        ROLE_ADMIN = 1,
        ROLE_VERIFIER = 2,
        ROLE_COLLECTOR = 3,
        ROLE_READER = 4;

    public static $roles = [
        self::ROLE_ADMIN => 'ROLE_ADMIN',
        self::ROLE_VERIFIER => 'ROLE_VERIFIER',
        self::ROLE_COLLECTOR => 'ROLE_COLLECTOR',
        self::ROLE_READER => 'ROLE_READER',
    ];

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
     * @var int
     * @ORM\Column(name="role",type="integer")
     */
    private $role;

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

    /** @var string|null */
    private $plainPassword;

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

    private function __construct(string $name, string $email, int $role)
    {
        if (!array_key_exists($role, self::$roles)) {
            throw new InvalidArgumentException(sprintf('Invalid Role %s not in %s', $role, implode(', ', self::$roles)));
        }

        $this->name  = $name;
        $this->email = $email;
        $this->role  = $role;
        $this->salt  = hash('sha256', sprintf('{%s}(%s)-%s', $name, $email, uniqid('vinuva-user', true)));
    }

    public static function createAdmin(string $name, string $email): self
    {
        return new self($name, $email, self::ROLE_ADMIN);
    }

    public static function createVerifier(string $name, string $email, Country $country): self
    {
        $obj          = new self($name, $email, self::ROLE_VERIFIER);
        $obj->country = $country;

        return $obj;
    }

    public static function createCollector(string $name, string $email, Country $country): self
    {
        $obj          = new self($name, $email, self::ROLE_COLLECTOR);
        $obj->country = $country;

        return $obj;
    }

    public static function createReader(string $name, string $email, Country $country): self
    {
        $obj          = new self($name, $email, self::ROLE_READER);
        $obj->country = $country;

        return $obj;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): void
    {
        if (!in_array($role, static::$roles, true)) {
            throw new InvalidArgumentException('Invalid Role');
        }

        $this->role = $role;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
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
        return [self::$roles[$this->role]];
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
