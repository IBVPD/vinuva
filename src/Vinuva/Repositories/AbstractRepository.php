<?php
declare(strict_types=1);

namespace Paho\Vinuva\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use DoctrineExtensions\Query\Mysql\GroupConcat;
use DoctrineExtensions\Query\Mysql\IfElse;
use InvalidArgumentException;
use Paho\Vinuva\Models\BaseDisease;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\User;

abstract class AbstractRepository
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
            ->innerJoin("$alias.country", 'c')
            ->orderBy("$alias.year",'DESC')
            ->addOrderBy("$alias.month", 'DESC');

        if (!$user->getCountry()) {
            return $queryBuilder;
        }

        $queryBuilder->andWhere("$alias.country = :country")->setParameter('country', $user->getCountry());

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

    public function getSummaryFilterQuery(): QueryBuilder
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->from($this->class, 'd')
            ->select('d')
            ->innerJoin('d.country','ctr')
            ->groupBy('ctr.id,d.year,d.month')
            ->orderBy('d.country,d.year,d.month');
    }

    public function getCollectionQuery(): QueryBuilder
    {
        $this->entityManager->getConfiguration()->addCustomStringFunction('GROUP_CONCAT', GroupConcat::class);
        $this->entityManager->getConfiguration()->addCustomStringFunction('IF', IfElse::class);

        return $this->entityManager
            ->createQueryBuilder()
            ->select('NEW Paho\Vinuva\Report\CaseVerification(ctr.name,h.name,\''.$this->class.'\',d.year,GROUP_CONCAT(IF(d.verified = 1,CONCAT(d.month,\'=V\'),CONCAT(d.month,\'=N\'))))')
            ->from($this->class, 'd')
            ->innerJoin('d.country','ctr')
            ->innerJoin('d.hospital','h')
            ->groupBy('ctr,h,d.year')
            ->orderBy('d.country,d.hospital,d.year,d.month');
    }

    public function getByHospitalFilterQuery(): QueryBuilder
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('d,h,ctr')
            ->from($this->class, 'd')
            ->innerJoin('d.country','ctr')
            ->innerJoin('d.hospital','h')
            ->orderBy('ctr.name,h.name')
            ->groupBy('h.id,d.year');
    }

    public function getByCountryFilterQuery(): QueryBuilder
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('d,h,ctr')
            ->from($this->class, 'd')
            ->innerJoin('d.country','ctr')
            ->innerJoin('d.hospital','h')
            ->orderBy('ctr.name,h.name')
            ->groupBy('ctr.id,d.year');
    }

    abstract public function getSummaryQuery(QueryBuilder $queryBuilder): array;
    abstract public function getByHospitalSummary(QueryBuilder $queryBuilder, array $results): array;
    abstract public function getByCountrySummary(QueryBuilder $queryBuilder, array $results): array;
}
