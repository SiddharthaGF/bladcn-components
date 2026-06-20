<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn;

use AiluraCode\Bladcn\Support\ClassResolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class BladcnServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassResolver::class);
    }

    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
            $blade->directive('asChild', function (string $expression): string {
                return "<?php echo app(\\AiluraCode\\Bladcn\\Support\\ClassResolver::class)->asChild({$expression}); ?>";
            });
        });
    }
}
