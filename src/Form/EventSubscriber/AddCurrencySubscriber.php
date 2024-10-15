<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Form\EventSubscriber;

use Sylius\Bundle\CurrencyBundle\Form\Type\CurrencyChoiceType;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

class AddCurrencySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event): void
    {
        $messagesNamespace = 'webgriffe_sylius_table_rate_plugin.ui.shipping_table_rate.';
        $form = $event->getForm();
        $resource = $event->getData();
        Assert::nullOrIsInstanceOf($resource, ResourceInterface::class);

        $form->add(
            'currency',
            CurrencyChoiceType::class,
            [
                'label' => $messagesNamespace . 'currency.label',
                'required' => true,
                'placeholder' => $messagesNamespace . 'currency.placeholder',
                'disabled' => $this->shouldCurrencyBeDisabled($resource),
            ],
        );
    }

    private function shouldCurrencyBeDisabled(?ResourceInterface $resource): bool
    {
        if (null === $resource) {
            return false;
        }

        return $resource->getId() !== null;
    }
}
