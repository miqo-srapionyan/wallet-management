<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __get($name)
    {
        if ($name == 'user')
        {
            $this->user = factory(User::class)->create();
        }

        return $this->$name;
    }
}
