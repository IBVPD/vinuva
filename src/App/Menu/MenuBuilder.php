<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use NS\ColorAdminBundle\Menu\MenuBuilder as BaseMenuBuilder;

class MenuBuilder extends BaseMenuBuilder
{
    public function createSidebarMenu(array $options): ItemInterface
    {
        $menu   = $this->factory->createItem('root');
        $admin  = $menu->addChild('Administration');
        $maint  = $menu->addChild('Maintenance');
        $surv   = $menu->addChild('Surveillance');
        $report = $menu->addChild('Reports');
        $help   = $menu->addChild('Help');

        return $menu;
    }
}
