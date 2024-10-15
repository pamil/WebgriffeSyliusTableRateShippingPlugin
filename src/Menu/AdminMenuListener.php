<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $configuration = $menu->getChild('configuration');
        if (null === $configuration) {
            return;
        }
        $configuration
            ->addChild(
                'webgriffe-sylius-table-rate-plugin-table-rates',
                ['route' => 'webgriffe_admin_shipping_table_rate_index'],
            )
            ->setLabel('webgriffe.ui.shipping_table_rates')
            ->setLabelAttribute('icon', 'pallet')
        ;
    }
}
