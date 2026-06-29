@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/table --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $tableClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'w-full caption-bottom text-sm',
    );

    $presetAttributes = [
        'data-slot' => 'table',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div class="relative w-full overflow-x-auto"
    data-slot="table-container">
    <table
        {{ $attributes->merge($presetAttributes)->class([$tableClass, $class]) }}>
        {{ $slot }}
    </table>
</div>
