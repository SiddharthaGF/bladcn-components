@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/input-otp --}}

@props([
    'name' => null,
    'value' => '',
    'maxlength' => 6,
    'disabled' => false,
    'style' => null,
    'class' => null,
    'containerClass' => null,
])

@php
    $containerClassResolver = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex items-center gap-2 has-disabled:opacity-50',
    );

    $presetAttributes = [
        'data-slot' => 'input-otp',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$containerClassResolver, $containerClass]) }}
    x-data="bladcnInputOtp({
        maxLength: @js((int) $maxlength),
        value: @js($value),
        disabled: @js($disabled),
    })">
    @if (filled($name))
        <input name="{{ $name }}"
            type="hidden"
            value="{{ $value }}"
            x-ref="hidden" />
    @endif

    <div @class(['disabled:cursor-not-allowed', $class])>
        {{ $slot }}
    </div>
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnInputOtp', (config = {}) => ({
                length: config.maxLength ?? 6,
                disabled: config.disabled ?? false,
                digits: Array.from({
                    length: config.maxLength ?? 6
                }, (_, index) => {
                    const initial = (config.value ?? '')
                        .charAt(index);

                    return initial || '';
                }),
                activeIndex: 0,

                init() {
                    this.syncHiddenInput();
                },

                get value() {
                    return this.digits.join('');
                },

                syncHiddenInput() {
                    if (this.$refs.hidden) {
                        this.$refs.hidden.value = this.value;
                    }

                    this.$dispatch('otp-change', {
                        value: this.value
                    });
                },

                focusSlot(index) {
                    if (this.disabled) {
                        return;
                    }

                    this.activeIndex = index;
                    this.$el.querySelectorAll(
                        '[data-slot="input-otp-slot"] input'
                    )[index]?.focus();
                },

                setDigit(index, rawValue) {
                    const digit = rawValue.replace(/\D/g, '')
                        .slice(-1);

                    this.digits[index] = digit;
                    this.syncHiddenInput();

                    if (digit && index < this.length - 1) {
                        this.focusSlot(index + 1);
                    }
                },

                onInput(index, event) {
                    this.setDigit(index, event.target.value);
                    event.target.value = this.digits[index];
                },

                onKeydown(index, event) {
                    if (event.key === 'Backspace') {
                        if (this.digits[index]) {
                            this.digits[index] = '';
                            this.syncHiddenInput();
                            event.preventDefault();

                            return;
                        }

                        if (index > 0) {
                            event.preventDefault();
                            this.focusSlot(index - 1);
                        }

                        return;
                    }

                    if (event.key === 'ArrowLeft' && index >
                        0) {
                        event.preventDefault();
                        this.focusSlot(index - 1);

                        return;
                    }

                    if (event.key === 'ArrowRight' && index <
                        this.length - 1) {
                        event.preventDefault();
                        this.focusSlot(index + 1);
                    }
                },

                onPaste(event) {
                    event.preventDefault();

                    const pasted = event.clipboardData
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, this.length);

                    pasted.split('').forEach((digit, index) => {
                        this.digits[index] = digit;
                    });

                    this.syncHiddenInput();
                    this.focusSlot(Math.min(pasted.length, this
                        .length - 1));
                },

                onFocus(index) {
                    this.activeIndex = index;
                },

                isActive(index) {
                    return this.activeIndex === index;
                },

                hasCaret(index) {
                    return this.isActive(index) && !this.digits[
                        index];
                },
            }));
        });
    </script>
@endPushOnce
