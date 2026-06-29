@blaze(fold: true)

@props([
    'errors' => [],
    'style' => null,
    'class' => null,
])

@php
    $messages = collect($errors)
        ->map(
            fn($error) => is_string($error)
                ? $error
                : $error['message'] ?? null,
        )
        ->filter()
        ->unique()
        ->values();

    $hasSlot = trim($slot->toHtml()) !== '';
    $hasMessages = $messages->isNotEmpty() || $hasSlot;

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'text-sm font-normal text-destructive',
    );

    $presetAttributes = [
        'role' => 'alert',
        'data-slot' => 'field-error',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

@if ($hasMessages)
    <div
        {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
        @if ($hasSlot)
            {{ $slot }}
        @elseif ($messages->count() === 1)
            {{ $messages->first() }}
        @else
            <ul class="ml-4 flex list-disc flex-col gap-1">
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
