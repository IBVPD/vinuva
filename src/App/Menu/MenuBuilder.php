<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var AuthorizationCheckerInterface */
    protected $authChecker;

    /** @var RequestVoter */
    private $voter;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, RequestVoter $voter)
    {
        $this->factory     = $factory;
        $this->authChecker = $authChecker;
        $this->voter       = $voter;
    }

    public function createSidebarMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
            $admin = $menu->addChild('Administration');
            $admin->addChild('Region', ['route' => 'adminRegionIndex']);
            $admin->addChild('Country', ['route' => 'adminCountryIndex']);
            $admin->addChild('Hospital', ['route' => 'adminHospitalIndex']);
            $admin->addChild('Users', ['route' => 'adminUserIndex']);
            $menu->addChild('Maintenance');
        }

        if ($this->authChecker->isGranted('ROLE_COLLECTOR')) {
            $surveillance = $menu->addChild('Surveillance');
            $surveillance->addChild('Meningitis', ['route' => 'meningitisIndex']);
            $surveillance->addChild('Pneumonia', ['route' => 'pneumoniaIndex']);
            $surveillance->addChild('Rotavirus', ['route' => 'rotavirusIndex']);
        }

        $report = $menu->addChild('Reports');
        $report->addChild('Monthly Collection');
        $report->addChild('Country Summary');
        $report->addChild('Hospital Summary');
        $report->addChild('Monthly Summary');
        $report->addChild('Hospitals');
        $report->addChild('Users by Organization');
        $report->addChild('Users by Role');

        $menu->addChild('Help');

        return $menu;
    }

    public function createTopMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Profile', ['route' => 'userProfile']);

        return $menu;
    }

    public function getCurrentMenuItem(ItemInterface $menu, Request $request): ?ItemInterface
    {
        foreach ($menu as $item) {
            if ($this->voter->matchItem($item)) {
                return $item;
            }

            if ($item->getChildren() && $currentChild = $this->getCurrentMenuItem($item, $request)) {
                return $currentChild;
            }
        }

        return null;
    }
}
