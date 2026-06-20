<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn;

use AiluraCode\Bladcn\Support\ClassResolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Override;

final class BladcnServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(ClassResolver::class);
    }

    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
            $blade->directive('asChild', fn (string $expression): string => sprintf('<?php echo app('.ClassResolver::class.'::class)->asChild(%s); ?>', $expression));
        });
    }
}
