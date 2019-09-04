<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use Paho\Vinuva\Models\User;

class UserRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllQB(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u,c')
            ->from(User::class, 'u')
            ->leftJoin('u.country', 'c')
            ->orderBy('u.name');
    }

    public function findByEmail(string $email): ?User
    {
        try {
            return $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
            return null;
        }
    }
}
