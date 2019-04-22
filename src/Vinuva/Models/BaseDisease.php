<?php

namespace Paho\Vinuva\Models;

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
}
