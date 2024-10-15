<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\EventSubscriber;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShippingMethod;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Calculator\TableRateShippingCalculator;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;
use Webmozart\Assert\Assert;

class TableRateDeleteSubscriber implements EventSubscriberInterface
{
    public function __construct(private ShippingMethodRepositoryInterface $shippingMethodRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['webgriffe.shipping_table_rate.pre_delete' => 'onTableRatePreDelete'];
    }

    public function onTableRatePreDelete(ResourceControllerEvent $event): void
    {
        $shippingTableRate = $event->getSubject();
        Assert::isInstanceOf($shippingTableRate, ShippingTableRate::class);

        $shippingMethods = $this->shippingMethodRepository->findBy(['calculator' => TableRateShippingCalculator::TYPE]);
        $foundMethods = [];
        /** @var ShippingMethod $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            foreach ($shippingMethod->getConfiguration() as $channelConfiguration) {
                /** @var string|null $channelTableRateCode */
                $channelTableRateCode = $channelConfiguration['table_rate'] ?? null;
                if ($channelTableRateCode !== null && $channelTableRateCode === $shippingTableRate->getCode()) {
                    $foundMethods[] = $shippingMethod->getCode();
                }
            }
        }

        if (count($foundMethods) > 0) {
            $event->stop(
                'webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.already_used_by_shipping_methods',
                ResourceControllerEvent::TYPE_ERROR,
                ['%shipping_methods%' => implode(', ', $foundMethods)],
                400,
            );
        }
    }
}
