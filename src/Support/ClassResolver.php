<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Support;

final class ClassResolver
{
    public function asChild(array $attributes = []): string
    {
        $class = $attributes['class'] ?? '';

        return htmlspecialchars((string) $class, ENT_QUOTES);
    }
}
