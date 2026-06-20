<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Tests\Laravel;

use AiluraCode\Bladcn\Support\ClassResolver;
use AiluraCode\Bladcn\Tests\BladcnTestCase;
use Illuminate\View\Compilers\BladeCompiler;

final class BladcnServiceProviderTest extends BladcnTestCase
{
    public function test_registers_class_resolver_as_singleton(): void
    {
        $app = $this->application();
        $first = $app->make(ClassResolver::class);
        $second = $app->make(ClassResolver::class);

        $this->assertSame($first, $second);
    }

    public function test_registers_as_child_blade_directive(): void
    {
        $compiler = $this->application()->make(BladeCompiler::class);
        $compiled = $compiler->compileString('@asChild($attributes)');

        $this->assertStringContainsString('asChild', $compiled);
        $this->assertStringContainsString(ClassResolver::class, $compiled);
    }
}
