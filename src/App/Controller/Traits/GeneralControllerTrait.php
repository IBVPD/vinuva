<?php

namespace App\Controller\Traits;

use Doctrine\ORM\EntityManagerInterface;
use NS\FlashBundle\Service\Messages;
use Symfony\Component\Routing\RouterInterface;

trait GeneralControllerTrait
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var RouterInterface */
    protected $router;

    /** @var Messages */
    protected $flash;

    /**
     * @param EntityManagerInterface $entityManager
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param RouterInterface $router
     * @required
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @param Messages $flash
     * @required
     */
    public function setFlash(Messages $flash): void
    {
        $this->flash = $flash;
    }
}
