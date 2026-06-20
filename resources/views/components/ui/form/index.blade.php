{{-- @see https://ui.shadcn.com/docs/components/form --}}
@props([
    'method' => 'POST',
    'action' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetAttributes = [
        'data-slot' => 'form',
        'method' => strtoupper($method) === 'GET' ? 'GET' : 'POST',
        'action' => $action,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<form {{ $attributes->merge($presetAttributes)->class(['space-y-6', $class]) }}>
    @if (!in_array(strtoupper($method), ['GET', 'POST'], true))
        @method($method)
    @endif

    @csrf

    {{ $slot }}
</form>
