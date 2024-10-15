<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Component\Currency\Model\CurrencyInterface;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public static function getCreateUpdatePageDefinedElements()
    {
        return [
            'form' => 'form[name="webgriffe_sylius_table_rate_plugin_shipping_table_rate"]',
            'code' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_code',
            'name' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_name',
            'currency' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_currency',
            'weightLimitToRate' => '#webgriffe_sylius_table_rate_plugin_shipping_table_rate_weightLimitToRate',
        ];
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            self::getCreateUpdatePageDefinedElements(),
        );
    }

    public function fillCode(string $code)
    {
        $this->getDocument()->fillField('Code', $code);
    }

    public function fillName(string $name)
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function fillCurrency(?CurrencyInterface $currency)
    {
        $this->getDocument()->selectFieldOption('Currency', $currency ? $currency->getCode() : '');
    }

    public function getFormValidationMessage(): string
    {
        return trim($this->getElement('form')->find('css', '.sylius-validation-error')->getText());
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
}
