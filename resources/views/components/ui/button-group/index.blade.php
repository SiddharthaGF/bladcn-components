@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/button-group --}}

@props([
    'class' => null,
    'orientation' => 'horizontal',
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'group/button-group items-stretch *:focus-visible:relative *:focus-visible:z-10 has-[>[data-slot=button-group]]:gap-2 has-[select[aria-hidden=true]:last-child]:[&>[data-slot=select-trigger]:last-of-type]:rounded-r-lg [&>[data-slot=select-trigger]:not([class*=\'w-\'])]:w-fit [&>input]:flex-1',
        )
        ->add(
            match ($orientation) {
                'horizontal'
                    => 'flex w-fit [&>*:not(:first-child)]:rounded-l-none [&>*:not(:first-child)]:border-l-0 [&>*:not(:last-child)]:rounded-r-none [&>[data-slot]:not(:has(~[data-slot]))]:rounded-r-lg!',
                'vertical'
                    => 'inline-grid w-max grid-cols-1 justify-items-stretch [&>*:not(:first-child)]:rounded-t-none [&>*:not(:first-child)]:border-t-0 [&>*:not(:last-child)]:rounded-b-none [&>[data-slot]:not(:has(~[data-slot]))]:rounded-b-lg!',
            },
        );

    $presetAttributes = [
        'role' => 'group',
        'data-slot' => 'button-group',
        'data-orientation' => $orientation,
    ];
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    {{ $slot }}
</div>
