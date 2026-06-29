@blaze(fold: true)

@props([
    'index' => 0,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex h-9 w-9 items-center justify-center border-y border-r border-input text-sm shadow-xs transition-all outline-none first:rounded-l-md first:border-l last:rounded-r-md dark:bg-input/30 data-[active=true]:z-10 data-[active=true]:border-ring data-[active=true]:ring-[3px] data-[active=true]:ring-ring/50 aria-invalid:border-destructive data-[active=true]:aria-invalid:border-destructive data-[active=true]:aria-invalid:ring-destructive/20 dark:data-[active=true]:aria-invalid:ring-destructive/40',
    );

    $presetAttributes = [
        'data-slot' => 'input-otp-slot',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->class([$presetClass, $class]) }}
    data-slot="input-otp-slot"
    x-bind:data-active="isActive({{ $index }})">
    <input aria-label="Digit {{ $index + 1 }}"
        class="absolute inset-0 size-full cursor-default opacity-0"
        inputmode="numeric"
        maxlength="1"
        pattern="[0-9]*"
        type="text"
        x-bind:disabled="disabled"
        x-bind:value="digits[{{ $index }}]"
        x-on:focus="onFocus({{ $index }})"
        x-on:input="onInput({{ $index }}, $event)"
        x-on:keydown="onKeydown({{ $index }}, $event)"
        x-on:paste="onPaste($event)" />
    <span aria-hidden="true"
        x-text="digits[{{ $index }}]"></span>
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center"
        x-cloak
        x-show="hasCaret({{ $index }})">
        <div class="animate-caret-blink bg-foreground h-4 w-px duration-1000">
        </div>
    </div>
</div>
