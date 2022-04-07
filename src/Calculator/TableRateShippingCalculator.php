<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Calculator;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface as BaseShipmentInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Exception\RateNotFoundException;
use Webgriffe\SyliusTableRateShippingPlugin\Resolver\TableRateResolverInterface;
use Webmozart\Assert\Assert;

final class TableRateShippingCalculator implements CalculatorInterface
{
    public const TYPE = 'table_rate';

    public function __construct(private TableRateResolverInterface $tableRateResolver)
    {
    }

    public function calculate(BaseShipmentInterface $shipment, array $configuration): int
    {
        Assert::isInstanceOf($shipment, ShipmentInterface::class);

        /** @noinspection PhpParamsInspection */
        $tableRate = $this->tableRateResolver->resolve($shipment, $configuration);

        try {
            return $tableRate->getRate($shipment->getShippingWeight());
        } catch (RateNotFoundException $e) {
            return 0;
        }
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
