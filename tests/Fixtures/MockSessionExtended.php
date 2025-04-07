<?php

namespace App\Tests\Fixtures;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MockSessionExtended
{
    public function __construct(private readonly SessionInterface $session)
    {
    }

    public function set(string $key, mixed $value): void
    {
        $this->session->set($key, $value);
        $this->session->save();
    }

    public function get(string $key): mixed
    {
        return $this->session->get($key);
    }
}