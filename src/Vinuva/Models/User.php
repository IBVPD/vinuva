<?php
declare(strict_types=1);

namespace Paho\Vinuva\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="u_login_idx",columns={"login"})})
 * @UniqueEntity(fields={"login"})
 */
class User implements UserInterface
{
    public const
        ROLE_ADMIN = 1,
        ROLE_VERIFIER = 2,
        ROLE_COLLECTOR = 3,
        ROLE_READER = 4;

    public static $roleLabels = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_VERIFIER => 'Verifier',
        self::ROLE_COLLECTOR => 'Collector',
        self::ROLE_READER => 'Reader',
    ];

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
     * @ORM\Column(name="login", type="string", length=190)
     */
    private $login;

    /**
     * @var string
     * @ORM\Column(name="email",type="string", length=190)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(name="password",type="string", nullable=true)
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
     * @var bool
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string|null
     * @ORM\Column(name="locale", type="string", length=12, nullable=true)
     */
    private $locale;

    /**
     * @var Country|null
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    /**
     * @var Collection|Hospital[]
     * @ORM\ManyToMany(targetEntity="Hospital")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hospitals;

    /**
     * @var string|null
     * @ORM\Column(name="phone",type="string", length=128, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     * @ORM\Column(name="address",type="string", nullable=true)
     */
    private $address;

    private function __construct(string $name, string $login, string $email, int $role)
    {
        if (!array_key_exists($role, self::$roles)) {
            throw new InvalidArgumentException(sprintf('Invalid Role %s not in %s', $role, implode(', ', self::$roles)));
        }

        $this->hospitals = new ArrayCollection();
        $this->active = true;
        $this->name = $name;
        $this->login = $login;
        $this->email = $email;
        $this->role = $role;
        $this->salt = hash('sha256', sprintf('{%s}(%s)-%s', $name, $email, uniqid('vinuva-user', true)));
    }

    public static function createAdmin(string $name, string $login, string $email): self
    {
        return new self($name, $login, $email, self::ROLE_ADMIN);
    }

    public static function createVerifier(string $name, string $login, string $email, Country $country, ?array $hospitals): self
    {
        if ($hospitals) {
            static::verifyHospitals($hospitals);
        }

        $obj = new self($name, $login, $email, self::ROLE_VERIFIER);
        $obj->country = $country;
        $obj->hospitals = new ArrayCollection($hospitals ?? []);

        return $obj;
    }

    public static function createCollector(string $name, string $login, string $email, Country $country, ?array $hospitals): self
    {
        if ($hospitals) {
            static::verifyHospitals($hospitals);
        }

        $obj = new self($name, $login, $email, self::ROLE_COLLECTOR);
        $obj->country = $country;
        $obj->hospitals = new ArrayCollection($hospitals ? iterator_to_array($hospitals) : []);

        return $obj;
    }

    public static function createReader(string $name, string $login, string $email, Country $country, ?array $hospitals): self
    {
        if ($hospitals) {
            static::verifyHospitals($hospitals);
        }

        $obj = new self($name, $login, $email, self::ROLE_READER);
        $obj->country = $country;
        $obj->hospitals = new ArrayCollection($hospitals ?? []);

        return $obj;
    }

    protected static function verifyHospitals(array $hospitals): void
    {
        $countryId = null;
        foreach ($hospitals as $hospital) {
            if (!$hospital instanceof Hospital) {
                throw new InvalidArgumentException('Invalid object');
            }

            if ($countryId === null) {
                $countryId = $hospital->getCountry()->getId();
            }
            if ($hospital->getCountry()->getId() !== $countryId) {
                throw new InvalidArgumentException('Hospitals must be from the same country');
            }
        }
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

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getRoleLabel(): string
    {
        return self::$roleLabels[$this->role] ?? 'Unknown';
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    }

    public function getHospitals(): Collection
    {
        return $this->hospitals;
    }

    public function setHospitals(array $hospitals): void
    {
        $this->hospitals = new ArrayCollection($hospitals);
    }

    public function getRoles(): array
    {
        return [self::$roles[$this->role]];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
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
