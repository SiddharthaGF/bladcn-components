<?php

declare(strict_types=1);

namespace App\Providers;

use AiluraCode\Bladcn\Support\ClassResolver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class BladcnServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassResolver::class);
    }

    public function boot(): void
    {
        Blade::directive('asChild', function (string $expression): string {
            return "<?php echo app(\\AiluraCode\\Bladcn\\Support\\ClassResolver::class)->asChild({$expression}); ?>";
        });
    }
}
