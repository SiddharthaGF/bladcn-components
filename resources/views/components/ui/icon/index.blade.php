@blaze(fold: false)
{{-- @see https://github.com/mallardduck/blade-lucide-icons --}}

@props([
    'name' => null,
    'style' => null,
    'class' => null,
])

@php
    if (!filled($name)) {
        throw new \InvalidArgumentException(
            'The [name] prop is required for [x-ui.icon].',
        );
    }

    $component = 'lucide-' . \Illuminate\Support\Str::kebab($name);
@endphp

<x-dynamic-component :component="$component"
    {{ $attributes->class($class) }} />
