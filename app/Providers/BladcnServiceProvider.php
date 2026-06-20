<?php

declare(strict_types=1);

namespace App\Providers;

use AiluraCode\Bladcn\Support\ClassResolver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
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
        Blade::directive('asChild', fn (string $expression): string => sprintf('<?php echo app('.ClassResolver::class.'::class)->asChild(%s); ?>', $expression));
    }
}
