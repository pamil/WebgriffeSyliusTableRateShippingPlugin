<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Resolver;

use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webgriffe\SyliusTableRateShippingPlugin\Form\Type\Shipping\Calculator\TableRateConfigurationType;
use Webmozart\Assert\Assert;

final class TableRateResolver implements TableRateResolverInterface
{
    /**
     * @param RepositoryInterface<ShippingTableRate> $tableRateRepository
     */
    public function __construct(private RepositoryInterface $tableRateRepository)
    {
    }

    public function resolve(ShipmentInterface $shipment, array $calculatorConfig): ShippingTableRate
    {
        $order = $shipment->getOrder();
        if (null === $order) {
            throw new \RuntimeException('Cannot resolve a table rate, there\'s no order for this shipment.');
        }
        $channel = $order->getChannel();
        if (null === $channel) {
            throw new \RuntimeException(
                'Cannot resolve a table rate, there\'s no channel for this shipment\'s order.'
            );
        }
        $channelCode = $channel->getCode();

        if (!isset($calculatorConfig[$channelCode])) {
            $shippingMethod = $shipment->getMethod();
            if (null === $shippingMethod) {
                throw new MissingChannelConfigurationException(
                    sprintf(
                        'This shipment has no configuration for channel "%s".',
                        $channel->getName()
                    )
                );
            }

            throw new MissingChannelConfigurationException(
                sprintf(
                    'Shipping method "%s" has no configuration for channel "%s".',
                    $shippingMethod->getName(),
                    $channel->getName()
                )
            );
        }

        /** @var ShippingTableRate $tableRate */
        $tableRate = $calculatorConfig[$channelCode][TableRateConfigurationType::TABLE_RATE_FIELD_NAME];
        $tableRate = $this->tableRateRepository->findOneBy(['code' => $tableRate->getCode()]);
        Assert::isInstanceOf($tableRate, ShippingTableRate::class);

        return $tableRate;
    }
}
