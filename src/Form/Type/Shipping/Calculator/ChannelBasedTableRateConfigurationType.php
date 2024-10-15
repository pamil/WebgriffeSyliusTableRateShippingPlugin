<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type\Shipping\Calculator;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChannelBasedTableRateConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'entry_type' => TableRateConfigurationType::class,
                'entry_options' => function (ChannelInterface $channel): array {
                    return ['label' => $channel->getName(), 'currency' => $channel->getBaseCurrency()];
                },
            ],
        );
    }

    public function getParent(): string
    {
        return ChannelCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'webgriffe_sylius_table_rate_shipping_plugin_calculator_channel_based_table_rate';
    }
}
