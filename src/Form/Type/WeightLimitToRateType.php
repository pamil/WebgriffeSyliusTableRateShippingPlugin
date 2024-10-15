<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\Type;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WeightLimitToRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $messagesNamespace = 'webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.';
        $builder
            ->add(
                'weightLimit',
                NumberType::class,
                [
                    'label' => $messagesNamespace . 'weightLimit',
                    'scale' => 2,
                    'required' => true,
                    'constraints' => [new NotBlank(['groups' => 'sylius'])],
                ],
            )
            ->add(
                'rate',
                MoneyType::class,
                [
                    'label' => $messagesNamespace . 'rate',
                    'required' => true,
                    'constraints' => [new NotBlank(['groups' => 'sylius'])],
                ],
            )
        ;
    }
}
