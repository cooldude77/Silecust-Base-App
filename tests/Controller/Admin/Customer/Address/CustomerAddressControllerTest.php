<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Tests\Controller\Admin\Customer\Address;

use Silecust\WebShop\Service\Testing\Fixtures\CustomerAddressFixture;
use Silecust\WebShop\Service\Testing\Fixtures\CustomerFixture;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\LocationFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

/**
 * Test Urls related to admin of Customer Address
 */
class CustomerAddressControllerTest extends WebTestCase
{

    use HasBrowser, EmployeeFixture, CustomerFixture, CustomerAddressFixture, SelectElement, LocationFixture, Factories;


    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreateOwnAddress()
    {
        $uri = "/my/address/create";


        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSee('Line1')
            ->assertSuccessful();


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEditOwnAddress()
    {
        $this->createCustomerAddressA($this->customerA);

        $uri = "/my/address/{$this->customerAddressShippingForCustomerA->getId()}/edit";

        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSee('Line1')
            ->assertSuccessful();


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testListAndSearchAddress()
    {
        $this->createCustomerAddressA($this->customerA);

        $uri = "/my/addresses";

        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // Test: If the address is shown
            ->visit($uri)
            ->assertSee($this->customerAddressBillingForCustomerA->getLine1())
            ->assertSee($this->customerAddressShippingForCustomerA->getLine1())
            ->assertSuccessful()
            // Test: Is Search success
            ->fillField("search_form[searchTerm]", $this->customerAddressBillingForCustomerA->getLine1())
            ->click('button[name="search"]')
            ->assertSee($this->customerAddressBillingForCustomerA->getLine1())
            // Test: Search produces nothing
            ->fillField("search_form[searchTerm]", "No Address In Search")
            ->click('button[name="search"]')
            ->assertNotSee($this->customerAddressBillingForCustomerA->getLine1())
            ->assertNotSee($this->customerAddressShippingForCustomerA->getLine1());


    }


    /**
     * @return void
     */
    public function testDeleteAddress()
    {

        $this->createCustomerAddressA($this->customerA);
        $address = $this->customerAddressShippingForCustomerA;

        $uri = "/my/address/{$this->customerAddressShippingForCustomerA->getId()}/delete";

        $this
            ->browser()
            ->visit('/')
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForCustomerA->object());
            })
            // fill all remaining fields too
            ->visit($uri)
            ->assertSuccessful();


    }


    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->createEmployeeFixtures();
        $this->createCustomerFixtures();
        $this->createLocationFixtures();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

}
