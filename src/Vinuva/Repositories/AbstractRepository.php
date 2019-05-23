<?php
declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use InvalidArgumentException;
use Paho\Vinuva\Models\BaseDisease;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\User;

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

    public function getQueryBuilder(User $user, string $alias = 'd'): QueryBuilder
    {
        if (in_array($alias, ['c', 'h'], true)) {
            throw new InvalidArgumentException('Alias cannot be c or h');
        }

        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select("$alias,h,c")
            ->from($this->class, $alias)
            ->innerJoin("$alias.hospital", 'h')
            ->innerJoin("$alias.country", 'c');

        if (!$user->getCountry()) {
            return $queryBuilder;
        }

        $hospitalCount = count($user->getHospitals());
        if ($hospitalCount > 0) {
            if ($hospitalCount) {
                return $queryBuilder->andWhere('h.id = :hId')->setParameter('hId', $user->getHospitals()->first()->getId());
            }

            $ids = [];
            foreach ($user->getHospitals() as $hospital) {
                $ids[] = $hospital->getId();
            }

            return $queryBuilder->andWhere('h.id IN (:hIds)')->setParameter('hIds', $ids);
        }

        return $queryBuilder;
    }
}
