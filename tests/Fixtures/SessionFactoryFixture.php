<?php

namespace App\Tests\Fixtures;

use App\Tests\Utility\MySessionFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 */
trait SessionFactoryFixture
{

    /**
     * @var MockSessionExtended
     *             Session Factory will return that session from the time user has been logged in
     *             The old app session keys may exist and need to be reset in their own function calls
     *             before any test should be called
     *
     */
    private MockSessionExtended $session;

    /**
     * @param KernelBrowser $kernelBrowser
     *
     * @return void
     */
    public function createSession(KernelBrowser $kernelBrowser): void
    {

        /** @var MySessionFactory $factory */
        $factory = $kernelBrowser->getContainer()->get('session.factory');

        if ($kernelBrowser->getRequest()->getSession() != null)
            $this->session = new MockSessionExtended($kernelBrowser->getRequest()->getSession());
        else $this->session = new MockSessionExtended($factory->createSession());

    }
}