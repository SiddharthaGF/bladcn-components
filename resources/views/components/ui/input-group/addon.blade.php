@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/input-group --}}

@props([
    'align' => 'inline-start',
    'style' => null,
    'class' => null,
])

@php
    $alignClass = match ($align) {
        'inline-end'
            => 'order-last pr-3 has-[>button]:mr-[-0.45rem] has-[>kbd]:mr-[-0.35rem]',
        'block-start'
            => 'order-first w-full justify-start px-3 pt-3 group-has-[>input]/input-group:pt-2.5 [.border-b]:pb-3',
        'block-end'
            => 'order-last w-full justify-start px-3 pb-3 group-has-[>input]/input-group:pb-2.5 [.border-t]:pt-3',
        default
            => 'order-first pl-3 has-[>button]:ml-[-0.45rem] has-[>kbd]:ml-[-0.35rem]',
    };

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'flex h-auto cursor-text items-center justify-center gap-2 py-1.5 text-sm font-medium text-muted-foreground select-none group-data-[disabled=true]/input-group:opacity-50 [&>kbd]:rounded-[calc(var(--radius)-5px)] [&>svg:not([class*=\'size-\'])]:size-4',
        )
        ->add($alignClass);

    $presetAttributes = [
        'role' => 'group',
        'data-slot' => 'input-group-addon',
        'data-align' => $align,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-on:click="if (!$event.target.closest('button')) { $el.parentElement?.querySelector('input')?.focus() }">
    {{ $slot }}
</div>
