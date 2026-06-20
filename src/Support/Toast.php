<?php

declare(strict_types=1);

namespace AiluraCode\Bladcn\Support;

use Illuminate\Support\Facades\Session;

use function is_array;

final class Toast
{
    public const SESSION_KEY = 'bladcn_toast';

    public const LIVEWIRE_EVENT = 'toast';

    /**
     * @return array<string, mixed>|null
     */
    public static function fromSession(): ?array
    {
        $toast = Session::get(self::SESSION_KEY);

        if (! is_array($toast)) {
            return null;
        }

        /** @var array<string, mixed> $toast */
        return $toast;
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array{title: string, variant: string, description?: string, position?: string, duration?: int}
     */
    public static function make(string $title, array $options = []): array
    {
        $variant = $options['variant'] ?? 'default';

        return array_filter([
            'title' => $title,
            'variant' => is_string($variant) ? $variant : 'default',
            'description' => isset($options['description']) && is_string($options['description'])
                ? $options['description']
                : null,
            'position' => isset($options['position']) && is_string($options['position'])
                ? $options['position']
                : null,
            'duration' => isset($options['duration']) && is_int($options['duration'])
                ? $options['duration']
                : null,
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public static function flash(string $title, array $options = []): void
    {
        Session::flash(self::SESSION_KEY, self::make($title, $options));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public static function success(string $title, array $options = []): void
    {
        $options['variant'] = 'success';

        self::flash($title, $options);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public static function info(string $title, array $options = []): void
    {
        $options['variant'] = 'info';

        self::flash($title, $options);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public static function warning(string $title, array $options = []): void
    {
        $options['variant'] = 'warning';

        self::flash($title, $options);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public static function error(string $title, array $options = []): void
    {
        $options['variant'] = 'destructive';

        self::flash($title, $options);
    }
}
