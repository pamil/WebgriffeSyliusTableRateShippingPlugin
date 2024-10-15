<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ShippingMethod;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\CreatePageInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\IndexPageInterface;
use Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Page\TableRate\UpdatePageInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

class ManagingTableRatesContext implements Context
{
    public function __construct(
        private IndexPageInterface $indexPage,
        private CreatePageInterface $createPage,
        private UpdatePageInterface $updatePage,
    ) {
    }

    /**
     * @When I am browsing the list of table rates
     *
     * @throws UnexpectedPageException
     */
    public function iAmBrowsingTheListOfTableRates(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Then I should see :number table rate(s) in the list
     */
    public function iShouldSeeZeroTableRatesInTheList(int $number = 0)
    {
        Assert::same($number, $this->indexPage->countItems());
    }

    /**
     * @Then I should see the :shippingTableRate table rate in the list
     */
    public function iShouldSeeTheTableRateInTheList(ShippingTableRate $shippingTableRate)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $shippingTableRate->getName()]));
    }

    /**
     * @When I add a shipping table rate named :name for currency :currency
     */
    public function iAddAShippingTableRateNamedForCurrency(string $name, CurrencyInterface $currency)
    {
        $this->createPage->open();
        $this->createPage->fillCode(StringInflector::nameToUppercaseCode($name));
        $this->createPage->fillName($name);
        $this->createPage->fillCurrency($currency);
        $this->createPage->addRate(random_int(1, 10), random_int(500, 1000));
        $this->createPage->create();
    }

    /**
     * @When I delete the :shippingTableRate table rate
     */
    public function iDeleteTheTableRate(ShippingTableRate $shippingTableRate)
    {
        $this->indexPage->deleteResourceOnPage(['name' => $shippingTableRate->getName()]);
    }

    /**
     * @Given I want to modify the :shippingTableRate table rate
     */
    public function iWantToModifyTheTableRate(ShippingTableRate $shippingTableRate): void
    {
        $this->updatePage->open(['id' => $shippingTableRate->getId()]);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I change its code to :code
     */
    public function iChangeItsCodeTo(string $code)
    {
        $this->createPage->fillCode($code);
    }

    /**
     * @When I change its name to :name
     */
    public function iChangeItsNameTo(string $name)
    {
        $this->createPage->fillName($name);
    }

    /**
     * @Then /^(this shipping table rate) name should be "([^"]+)"$/
     */
    public function thisShippingTableRateNameShouldBe(ShippingTableRate $shippingTableRate, string $code)
    {
        $this->updatePage->open(['id' => $shippingTableRate->getId()]);
        $this->updatePage->hasResourceValues(['name' => $code]);
    }

    /**
     * @When /^I add a new rate of ("[^"]+") for shipments up to (\d+) kg$/
     */
    public function iAddANewRateOfForShipmentsUpToKg(int $rate, int $weightLimit): void
    {
        $this->updatePage->addRate($rate, $weightLimit);
    }

    /**
     * @Then /^(this shipping table rate) should have (\d+) rates$/
     */
    public function thisShippingTableRateShouldHaveRates(ShippingTableRate $shippingTableRate, int $count)
    {
        $this->indexPage->open();

        Assert::eq($this->indexPage->getTableRateRatesCount($shippingTableRate), $count);
    }

    /**
     * @When I try to add a new shipping table
     */
    public function iTryToAddANewShippingTable()
    {
        $this->createPage->open();
    }

    /**
     * @When I specify its code as :code
     */
    public function iSpecifyItsCodeAs(string $code)
    {
        $this->createPage->fillCode($code);
    }

    /**
     * @When I specify its currency as :currency
     */
    public function iSpecifyItsCurrencyAs(CurrencyInterface $currency)
    {
        $this->createPage->fillCurrency($currency);
    }

    /**
     * @When I do not specify its name
     */
    public function iDoNotSpecifyItsName()
    {
        $this->createPage->fillName('');
    }

    /**
     * @When I try to add it
     */
    public function iTryToAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @Then I should be notified that :element is required
     */
    public function iShouldBeNotifiedThatIsRequired($element)
    {
        Assert::same($this->createPage->getValidationMessage($element), 'This value should not be blank.');
    }

    /**
     * @When I specify its name as :name
     */
    public function iSpecifyItsNameAs(string $name)
    {
        $this->createPage->fillName($name);
    }

    /**
     * @When I do not specify its code
     */
    public function iDoNotSpecifyItsCode()
    {
        $this->createPage->fillCode('');
    }

    /**
     * @When I do not specify its currency
     */
    public function iDoNotSpecifyItsCurrency()
    {
        $this->createPage->fillCurrency(null);
    }

    /**
     * @When I do not specify any rate
     */
    public function iDoNotSpecifyAnyRate()
    {
        // Simply we don't add the rate
    }

    /**
     * @Then I should be notified that at least one rate is required
     */
    public function iShouldBeNotifiedThatAtLeastOneRateIsRequired()
    {
        Assert::same(
            $this->createPage->getFormValidationMessage(),
            'You should specify at least one rate for this table rate.',
        );
    }

    /**
     * @Then the code field should be disabled
     */
    public function theCodeFieldShouldBeDisabled()
    {
        Assert::true($this->updatePage->isCodeDisabled());
    }

    /**
     * @Then the :shippingTableRate table rate should still have code :code
     */
    public function theShippingTableRateShouldStillHaveCode(ShippingTableRate $shippingTableRate, string $code)
    {
        Assert::eq($shippingTableRate->getCode(), $code);
    }

    /**
     * @Then the currency field should be disabled
     */
    public function theCurrencyFieldShouldBeDisabled()
    {
        Assert::true($this->updatePage->isCurrencyDisabled());
    }

    /**
     * @When I change its currency to :currency
     */
    public function iChangeItsCurrencyTo(CurrencyInterface $currency)
    {
        $this->createPage->fillCurrency($currency);
    }

    /**
     * @Then the :shippingTableRate table rate should still have :currency currency
     */
    public function theTableRateShouldStillHaveCurrency(
        ShippingTableRate $shippingTableRate,
        CurrencyInterface $currency,
    ) {
        Assert::same($shippingTableRate->getCurrency()->getCode(), $currency->getCode());
    }

    /**
     * @Then I should be notified that code has to be unique
     */
    public function iShouldBeNotifiedThatCodeHasToBeUnique()
    {
        $this->createPage->getValidationMessage(
            'code',
            'There\'s another shipping table rate with the same code. The code has to be unique.',
        );
    }

    /**
     * @Then I should be notified that the table rate couldn't be deleted because is already used by the :shippingMethod shipping method
     */
    public function iShouldBeNotifiedThatTheTableRateCouldntBeDeletedBecauseIsAlreadyUsedByTheShippingMethod(
        ShippingMethod $shippingMethod,
    ) {
        Assert::contains(
            $this->indexPage->getValidationMessage(),
            'The table rate cannot be deleted because is currently used by the following shipping methods: ' .
            $shippingMethod->getCode(),
        );
    }

    /**
     * @Then the :shippingTableRate shipping table rate should still be there
     */
    public function theShippingTableRateShouldStillBeThere(ShippingTableRate $shippingTableRate)
    {
        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $shippingTableRate->getName()]));
    }
}
