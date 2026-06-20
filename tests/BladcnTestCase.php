<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Tests;

use AiluraCode\Bladcn\BladcnServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Override;
use RuntimeException;

abstract class BladcnTestCase extends OrchestraTestCase
{
    #[Override]
    protected function defineEnvironment($app): void
    {
        $app->register(BladcnServiceProvider::class);
    }

    protected function application(): Application
    {
        $app = $this->app;

        throw_unless($app instanceof Application, RuntimeException::class, 'The Laravel application has not been bootstrapped.');

        return $app;
    }
}
