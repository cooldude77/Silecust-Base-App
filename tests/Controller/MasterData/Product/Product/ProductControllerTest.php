<?php

namespace App\Tests\Controller\MasterData\Product\Product;

use Silecust\WebShop\Factory\CategoryFactory;
use Silecust\WebShop\Factory\ProductFactory;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\ProductFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class ProductControllerTest extends WebTestCase
{

    use HasBrowser, ProductFixture, EmployeeFixture, Factories, SelectElement;

    protected function setUp(): void
    {
        $this->browser()->visit('/logout');
        $this->createEmployeeFixtures();
    }

    protected function tearDown(): void
    {
        $this->browser()->visit('/logout');

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testCreate()
    {

        $this->createProductFixtures();
        $uri = '/admin/product/create';

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            // do not fill category
            ->fillField('product_create_form[name]', 'Prod1')
            ->fillField('product_create_form[description]', 'Product 1')
            ->click('Save')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            // fill all
            ->use(function (Browser $browser) {
                $this->addOption(
                    $browser,
                    'select[name="product_create_form[category]"]',
                    $this->categoryA->getId()
                );
            })
            ->fillField('product_create_form[name]', 'Prod1')
            ->fillField('product_create_form[description]', 'Product 1')
            ->click('Save')
            ->assertSuccessful();

        $created = ProductFactory::find(array('name' => "Prod1"));

        $this->assertEquals("Prod1", $created->getName());
        $this->assertEquals("Product 1", $created->getDescription());
        $this->assertTrue($created->isIsActive());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $this->createProductFixtures();
        $uri = "/admin/product/{$this->productA->getId()}/edit";

        $visit = $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            // fill all
            ->visit($uri)
            ->use(function (Browser $browser) {
                $this->addOption(
                    $browser,
                    'select[name="product_edit_form[category]"]',
                    $this->categoryB->getId()
                );
            })
            ->fillField('product_edit_form[name]', 'Prod11')
            ->fillField('product_edit_form[description]', 'Product 11')
            ->fillField('product_edit_form[category]', $this->categoryB->getId())
            ->uncheckField('product_edit_form[isActive]')
            ->click('Save')
            ->assertSuccessful();

        $edited = ProductFactory::find($this->productA->getId());

        $this->assertEquals("Prod11", $edited->getName());
        $this->assertEquals("Product 11", $edited->getDescription());
        $this->assertEquals($this->categoryB->getId(), $edited->getCategory()->getId());
        $this->assertFalse($edited->isIsActive());

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {
        $category = CategoryFactory::createOne(['name' => 'Cat1',
            'description' => 'Category 1']);


        $product = ProductFactory::createOne(['category' => $category]);

        $id = $product->getId();
        $uri = "/admin/product/$id/display";

        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })->visit($uri)
            ->assertSuccessful();


    }


    public function testList()
    {

        $this->createProductFixtures();
        $uri = '/admin/product/list';
        $this->browser()->visit($uri)->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })->visit($uri)->assertSuccessful();

    }

    public function testListUsingSearchTerm()
    {

        $this->createProductFixtures();
        $uri = "/admin/product/list?searchTerm={$this->productA->getName()}";
        $this
            ->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForEmployee->object());
            })
            ->visit($uri)
            ->assertSee($this->productA->getName())
            ->assertNotSee($this->productB->getName())
            ->assertSuccessful();

    }


}
