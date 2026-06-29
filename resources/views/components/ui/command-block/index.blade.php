@blaze(fold: false)
{{-- Install command snippet tabs (project-specific). --}}

@props([
    'defaultValue' => null,
    'id' => null,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'divide-y divide-zinc-800 overflow-hidden rounded-lg border border-zinc-800',
    );

    $presetAttributes = [
        'id' => $id,
        'data-slot' => 'command-block',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnCommandBlock({
        defaultValue: @js($defaultValue),
    })">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnCommandBlock', (config = {}) => ({
                activeTab: config.defaultValue ?? null,
                isCopied: false,

                select(value) {
                    this.activeTab = value;
                },

                isActive(value) {
                    return this.activeTab === value;
                },

                get activeCommand() {
                    const panel = this.$root.querySelector(
                        `[data-slot="command-block-content"][data-value="${this.activeTab}"]`,
                    );

                    return panel?.dataset?.command ?? '';
                },

                copy() {
                    const text = this.activeCommand;
                    if (!text) return;

                    if (navigator.clipboard?.writeText) {
                        navigator.clipboard.writeText(text);
                    } else {
                        const textarea = document.createElement(
                            'textarea');
                        textarea.value = text;
                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        textarea.remove();
                    }

                    this.isCopied = true;
                    setTimeout(() => {
                        this.isCopied = false;
                    }, 2000);
                },
            }));
        });
    </script>
@endPushOnce
