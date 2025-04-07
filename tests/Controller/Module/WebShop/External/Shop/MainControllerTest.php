<?php

namespace App\Tests\Controller\Module\WebShop\External\Shop;

use App\Tests\Fixtures\CartFixture;
use App\Tests\Fixtures\CurrencyFixture;
use App\Tests\Fixtures\CustomerFixture;
use App\Tests\Fixtures\LocationFixture;
use App\Tests\Fixtures\OrderFixture;
use App\Tests\Fixtures\PriceFixture;
use App\Tests\Fixtures\ProductFixture;
use App\Tests\Fixtures\SessionFactoryFixture;
use App\Tests\Utility\FindByCriteria;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class MainControllerTest extends WebTestCase
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
        SessionFactoryFixture,
        Factories;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');


    }

    public function testWhenProductsExistButPriceDoesNotExist()
    {
        $this->createCustomerFixtures();
        $this->createProductFixtures();
        $this->createLocationFixtures();
        $this->createCurrencyFixtures($this->country);

        $this->browser()
            ->visit('/')
            ->assertSuccessful()
            ->assertSee("Base Price not found for product Prod name A")
            ->assertSee("Base Price not found for product Prod name B");

    }

    public function testWhenAnyProductDoesNotExist()
    {
        $this->browser()
            ->visit('/')
            ->assertSuccessful()
            /*     ->use(function (Browser $browser) {
                     $r = $browser->client()->getResponse();
                 })
              */
            ->assertSee("No products are available to list at the moment");

    }

    public
    function testShop()
    {

        // visit home , not logged in
        $this->browser()
            ->visit('/')
            ->assertSuccessful();

        //    ->assertSeeElement('a#logo-home-link');

    }
}
