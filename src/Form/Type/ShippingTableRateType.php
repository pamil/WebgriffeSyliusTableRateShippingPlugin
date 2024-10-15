<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Webgriffe\SyliusTableRateShippingPlugin\Form\EventSubscriber\AddCurrencySubscriber;

final class ShippingTableRateType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $messagesNamespace = 'webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.';
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(new AddCurrencySubscriber())
            ->add('name', TextType::class, ['label' => $messagesNamespace . 'name'])
            ->add(
                'weightLimitToRate',
                CollectionType::class,
                [
                    'label' => $messagesNamespace . 'weightLimitToRate.label',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => WeightLimitToRateType::class,
                ],
            )
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'webgriffe_sylius_table_rate_plugin_shipping_table_rate';
    }
}
