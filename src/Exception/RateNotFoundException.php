<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Exception;

use Throwable;
use Webgriffe\SyliusTableRateShippingPlugin\Entity\ShippingTableRate;

class RateNotFoundException extends \RuntimeException
{
    public function __construct(
        ShippingTableRate $shippingTableRate,
        float $weight,
        int $code = 0,
        Throwable $previous = null,
    ) {
        $message = sprintf(
            'The shipping table rate "%s" cannot find a rate for a weight of "%s"',
            $shippingTableRate->getCode(),
            $weight,
        );
        parent::__construct($message, $code, $previous);
    }
}
