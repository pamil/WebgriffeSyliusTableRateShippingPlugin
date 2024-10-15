<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusTableRateShippingPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductContext implements Context
{
    public function __construct(
        private ExampleFactoryInterface $productExampleFactory,
        private RepositoryInterface $productRepository,
    ) {
    }

    /**
     * @Transform :weight
     */
    public function transformWeight(string $weight): int
    {
        return (int) $weight;
    }

    /**
     * @Given the store has a product :productName which weights :weight kg
     */
    public function theStoreHasProductPricedWhichWeights(
        string $productName,
        int $weight,
    ): void {
        /** @var ProductInterface $product */
        $product = $this->productExampleFactory->create([
            'name' => $productName,
            'enabled' => true,
            'main_taxon' => null,
        ]);

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $product->getVariants()->first();

        $productVariant->setWeight((float) $weight);

        $this->productRepository->add($product);
    }
}
