<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __get($name)
    {
        if ($name === 'user') {
            /** @var \Illuminate\Contracts\Auth\Authenticatable user */
            $this->user = User::factory()->create();
        }

        return $this->$name;
    }
}
