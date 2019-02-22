<?php

namespace Autoluminescent\Multiauth\Tests;

use Autoluminescent\Multiauth\MultiAuthServiceProvider;
use Orchestra\Testbench\TestCase;


abstract class MultiAuthTestCase extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [MultiAuthServiceProvider::class];
    }
}
