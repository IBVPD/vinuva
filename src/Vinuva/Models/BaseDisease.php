<?php

namespace Paho\Vinuva\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

class BaseDisease
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country")
     */
    protected $country;

    /**
     * @var Hospital
     * @ORM\ManyToOne(targetEntity="Hospital")
     */
    protected $hospital;

    /**
     * @var int
     * @ORM\Column(name="year",type="integer")
     */
    protected $year;

    /**
     * @var int
     * @ORM\Column(name="month",type="integer")
     */
    protected $month;

    /**
     * @var int|null
     * @ORM\Column(name="under5",type="integer",nullable=true)
     */
    private $under5;

    /**
     * @var string|null
     * @ORM\Column(name="notifierComments", type="text", nullable=true)
     */
    private $notifierComments;

    /**
     * @var string|null
     * @ORM\Column(name="verifierComments", type="text", nullable=true)
     */
    private $verifierComments;

    /**
     * @var DateTime|null
     * @ORM\Column(name="verified", type="date", nullable=true)
     */
    private $verificationDate;

    public function __construct(Hospital $hospital, int $year, int $month)
    {
        $this->hospital = $hospital;
        $this->country  = $hospital->getCountry();
        $this->year     = $year;
        $this->month    = $month;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getHospital(): Hospital
    {
        return $this->hospital;
    }

    public function setHospital(Hospital $hospital): void
    {
        $this->hospital = $hospital;
        $this->country  = $hospital->getCountry();
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function getUnder5(): ?int
    {
        return $this->under5;
    }

    public function setUnder5(?int $under5): void
    {
        $this->under5 = $under5;
    }

    public function getNotifierComments(): ?string
    {
        return $this->notifierComments;
    }

    public function setNotifierComments(?string $notifierComments): void
    {
        $this->notifierComments = $notifierComments;
    }

    public function getVerifierComments(): ?string
    {
        return $this->verifierComments;
    }

    public function setVerifierComments(?string $verifierComments): void
    {
        $this->verifierComments = $verifierComments;
    }

    public function isVerified(): bool
    {
        return $this->verificationDate instanceof DateTime;
    }

    public function getVerificationDate(): ?DateTime
    {
        return $this->verificationDate;
    }

    public function setVerificationDate(?DateTime $verificationDate): void
    {
        $this->verificationDate = $verificationDate;
    }
}
