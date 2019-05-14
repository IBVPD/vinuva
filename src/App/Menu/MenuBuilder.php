<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var AuthorizationCheckerInterface */
    protected $authChecker;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker)
    {
        $this->factory     = $factory;
        $this->authChecker = $authChecker;
    }

    public function createSidebarMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
            $admin = $menu->addChild('Administration');
            $maint = $menu->addChild('Maintenance');
        }

        if ($this->authChecker->isGranted('ROLE_COLLECTOR')) {
            $surv = $menu->addChild('Surveillance');
            $surv->addChild('Meningitis', ['route' => 'meningitisCreate']);
            $surv->addChild('Pneumonia', ['route' => 'pneumoniaCreate']);
            $surv->addChild('Rotavirus', ['route' => 'rotavirusCreate']);
        }

        $report = $menu->addChild('Reports');
        $report->addChild('Monthly Collection');
        $report->addChild('Country Summary');
        $report->addChild('Hospital Summary');
        $report->addChild('Monthly Summary');
        $report->addChild('Hospitals');
        $report->addChild('Users by Organization');
        $report->addChild('Users by Role');

        $help   = $menu->addChild('Help');

        return $menu;
    }

    public function createTopMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Profile', ['route' => 'userProfile']);

        return $menu;
    }
}
