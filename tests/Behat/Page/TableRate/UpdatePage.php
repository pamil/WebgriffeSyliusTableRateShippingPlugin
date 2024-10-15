<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Behaviour\ChecksCodeImmutability;
use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ChecksCodeImmutability;

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            CreatePage::getCreateUpdatePageDefinedElements(),
        );
    }

    public function addRate(int $rate, int $weightLimit)
    {
        $weightLimitToRateField = $this->getDocument()->findById(
            'webgriffe_sylius_table_rate_plugin_shipping_table_rate_weightLimitToRate',
        );
        $addRateButton = $weightLimitToRateField->findLink('Add');
        $addRateButton->click();
        $item = $weightLimitToRateField->find('css', '[data-form-collection=item]:last-child');
        $item->fillField('Weight limit', $weightLimit);
        $item->fillField('Rate', $rate / 100);
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    protected function getCodeElement(): NodeElement
    {
        return $this->getElement('code');
    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function isCurrencyDisabled(): bool
    {
        return $this->getElement('currency')->getAttribute('disabled') === 'disabled';
    }
}
