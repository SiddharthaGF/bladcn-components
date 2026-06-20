@blaze(fold: false)
{{-- Primitiva interna (asChild). Sin equivalente directo en shadcn/ui. --}}
@props([
    'defaultTag' => 'div',
    'asChild' => false,
])

@if ($asChild)
    {!! \AiluraCode\Bladcn\Support\AsChildSlot::render(
        $slot->toHtml(),
        $attributes,
    ) !!}
@else
    <{{ $defaultTag }} {{ $attributes }}>{{ $slot }}
        </{{ $defaultTag }}>
@endif
