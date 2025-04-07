<?php

namespace App\Tests\Controller\Module\WebShop\External\Payment;

use Silecust\WebShop\Entity\OrderHeader;
use Silecust\WebShop\Entity\OrderJournal;
use Silecust\WebShop\Entity\OrderPayment;
use Silecust\WebShop\Service\Transaction\Order\Status\OrderStatusTypes;
use App\Tests\Fixtures\CartFixture;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\OrderItemFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class PaymentControllerTest extends WebTestCase
{
    use HasBrowser,
        CurrencyFixture,
        CustomerFixture,
        ProductFixture,
        PriceFixture,
        LocationFixture,
        FindByCriteria,
        CartFixture,
        OrderFixture,
        OrderItemFixture,
        SessionFactoryFixture,
        Factories;
    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }


    public function testOnPaymentStart()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);
        $this->createPriceFixtures($this->productA, $this->productB, $this->currency);
        $this->createOpenOrderFixtures($this->customer);
        $this->createOrderItemsFixture($this->openOrderHeader, $this->productA, $this->productB);

        $uri = "/payment/order/{$this->openOrderHeader->getGeneratedId()}/start";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());
            })
            // start from here because shipping call is triggered here and shipping costs are added here
            ->visit('/checkout/order/view')
            ->assertSuccessful()
            ->visit($uri)
            ->assertSee(4930.5);
    }

    public function testOnPaymentSuccess()
    {
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
        $this->createOpenOrderFixtures($this->customer);

        $uri = "/payment/order/{$this->openOrderHeader->getGeneratedId()}/success";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());

            })
            ->interceptRedirects()
            ->post($uri,
                [
                    'body' => [
                        OrderPayment::PAYMENT_GATEWAY_RESPONSE =>
                            [
                                'payment_id' => 'An id'
                            ]
                    ]
                ])
            ->assertRedirectedTo("/order/{$this->openOrderHeader->getGeneratedId()}/success", 1);

        /** @var OrderHeader $header */
        $header = $this->findOneBy(
            OrderHeader::class, ['id' => $this->openOrderHeader->getId()]
        );
        self::assertEquals(
            OrderStatusTypes::ORDER_PAYMENT_COMPLETE,
            $header->getOrderStatusType()->getType()
        );

        $journal = $this->findOneBy(OrderJournal::class, ['orderHeader' => $this->openOrderHeader->object()]);

        $this->assertNotNull($journal);
    }

    public function testOnPaymentFailure()
    {
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
        $this->createOpenOrderFixtures($this->customer);

        $uri = "/payment/order/{$this->openOrderHeader->getGeneratedId()}/failure";

        $this->browser()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomer->object());

            })
            ->post($uri,
                [
                    'body' => [
                        OrderPayment::PAYMENT_GATEWAY_RESPONSE =>
                            [
                                'payment_id' => 'An id'
                            ]
                    ]
                ]);


        /** @var OrderHeader $header */
        $header = $this->findOneBy(
            OrderHeader::class, ['id' => $this->openOrderHeader->object()]
        );
        self::assertEquals(
            OrderStatusTypes::ORDER_PAYMENT_FAILED,
            $header->getOrderStatusType()->getType()
        );

        $journal = $this->findOneBy(OrderJournal::class, ['orderHeader' => $this->openOrderHeader->object()]);

        $this->assertNotNull($journal);
    }
}