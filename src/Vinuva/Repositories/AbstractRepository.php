<?php
declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnexpectedResultException;
use Paho\Vinuva\Models\BaseDisease;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;

class AbstractRepository
{
    /** @var string */
    protected $class;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(string $class, EntityManagerInterface $entityManager)
    {
        $this->class         = $class;
        $this->entityManager = $entityManager;
    }

    public function check(Country $country, Hospital $hospital, int $year, int $month): ?BaseDisease
    {
        try {
            return $this->entityManager->createQueryBuilder()
                ->select('d')
                ->from($this->class, 'd')
                ->where('d.country = :country AND d.hospital = :hospital AND d.year = :year AND d.month = :month')
                ->setParameters([
                    'country' => $country,
                    'hospital' => $hospital,
                    'year' => $year,
                    'month' => $month,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
            return null;
        }
    }
}
