<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\ShippingMethod;

use Sylius\Behat\Page\Admin\ShippingMethod\UpdatePage as BaseUpdatePage;

class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    public function getTableRateOptions(string $channelCode): array
    {
        return $this->getElement('table_rate', ['%channelCode%' => $channelCode])->findAll(
            'css',
            'option[value!=""]',
        );
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            ['table_rate' => '#sylius_shipping_method_configuration_%channelCode%_table_rate'],
        );
    }
}
