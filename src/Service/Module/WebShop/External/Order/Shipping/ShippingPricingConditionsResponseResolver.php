<?php

namespace App\Service\Module\WebShop\External\Order\Shipping;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Service\Transaction\Order\Header\Shipping\ShippingPricingConditionsResponseResolverInterface;

class ShippingPricingConditionsResponseResolver implements ShippingPricingConditionsResponseResolverInterface
{

    public function getShippingChargesConditionsFromAPI(OrderHeader $orderHeader): array
    {
        return [
            'condition1' => [
                'name' => 'Condition 1',
                'value' => 100.5,
                'data' => ['txnId' => 'txnId']]

        ];
    }
}