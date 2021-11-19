<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        /**
         * Go through all methods.
         * Run the method if the name is started with 'setUp'.
         * Exclude some default setup methods.
         */
        foreach (get_class_methods($this) as $method) {
            if (
                strlen($method) <= 5 ||
                strpos($method, 'setUp') !== 0 ||
                in_array($method, ['setUpTraits', 'setUpFaker', 'setUpBeforeClass'])
            ) continue;
            $this->$method();
        }

        return $uses;
    }
}
