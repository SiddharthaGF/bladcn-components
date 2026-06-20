<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Support;

use Override;
use Stringable;

final class ClassResolver implements Stringable
{
    /** @var list<string> */
    private array $segments = [];

    #[Override]
    public function __toString(): string
    {
        return implode(' ', $this->segments);
    }

    public function add(mixed $class): self
    {
        if ($class === null || $class === false) {
            return $this;
        }

        if (! is_string($class)) {
            return $this;
        }

        $trimmed = mb_trim($class);

        if ($trimmed === '') {
            return $this;
        }

        $this->segments[] = $trimmed;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function asChild(array $attributes = []): string
    {
        $class = $attributes['class'] ?? '';

        if (! is_string($class)) {
            $class = is_scalar($class) ? (string) $class : '';
        }

        return htmlspecialchars($class, ENT_QUOTES);
    }
}
