<?php

namespace App\Tests\Controller\MasterData\Employee;

use Silecust\WebShop\Factory\EmployeeFactory;
use Silecust\WebShop\Factory\UserFactory;
use Silecust\WebShop\Service\Testing\Fixtures\EmployeeFixture;
use Silecust\WebShop\Service\Testing\Fixtures\SuperAdminFixture;
use Silecust\WebShop\Service\Testing\Utility\SelectElement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;

class EmployeeControllerTest extends WebTestCase
{

    use HasBrowser, SuperAdminFixture, EmployeeFixture, SelectElement, Factories;

    protected function setUp(): void
    {
        $this->createSuperAdmin();
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
        $uri = '/admin/employee/create';

        $this->browser()
            ->visit($uri)
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForSuperAdmin->object());
            })
            ->visit($uri)
            ->fillField(
                'employee_create_form[firstName]', 'First Name'
            )->fillField(
                'employee_create_form[lastName]', 'Last Name'
            )
            ->fillField('employee_create_form[email]', 'x@y.com')
            ->fillField('employee_create_form[phoneNumber]', '+91999999999')
            // leaving it here as password is now generated randomly and is also not sent over to the employee
            //   ->fillField('employee_create_form[plainPassword]', '4534geget355$%^')
            ->click('Save')
            ->assertSuccessful();

        $created = EmployeeFactory::find(array('firstName' => 'First Name'));

        $this->assertEquals("First Name", $created->getFirstName());

        $user = $created->getUser();

        $this->assertTrue(in_array('ROLE_ADMIN', $created->getUser()->getRoles()));

    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testEdit()
    {

        $user = UserFactory::createOne();

        $employee = EmployeeFactory::createOne(['firstName' => "First Name", 'user' => $user]);

        $id = $employee->getId();

        $uri = "/admin/employee/$id/edit";

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForSuperAdmin->object());
            })
            ->visit($uri)
            ->fillField(
                'employee_edit_form[firstName]', 'New First Name'
            )->fillField(
                'employee_edit_form[middleName]', 'New Middle Name')
            ->fillField(
                'employee_edit_form[lastName]', 'New Last Name'
            )
            ->fillField('employee_edit_form[email]', 'f@g.com')
            ->fillField('employee_edit_form[phoneNumber]', '+9188888888')
            ->click('Save')
            ->assertSuccessful();

        $created = EmployeeFactory::find(array('firstName' => "New First Name"));

        $this->assertEquals("New First Name", $created->getFirstName());


    }

    /**
     * Requires this test extends Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
     * or Symfony\Bundle\FrameworkBundle\Test\WebTestCase.
     */
    public function testDisplay()
    {

        $employee = EmployeeFactory::createOne();

        $id = $employee->getId();
        $uri = "/admin/employee/$id/display";

        $this->browser()
            ->visit($uri)
            ->assertNotAuthenticated()
            ->use(callback: function (Browser $browser) {
                $browser->client()->loginUser($this->userForSuperAdmin->object());
            })
            ->visit($uri)
            ->assertSuccessful();


    }


    public function testList()
    {

        $uri = '/admin/employee/list';
        $this->browser()->visit($uri)->assertSuccessful();

    }


}
