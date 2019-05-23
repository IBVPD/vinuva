<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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
}
