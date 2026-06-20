@blaze(fold: true)

@props([
    'showCollapse' => false,
    'showCopy' => false,
])

@php
    $attrs = [
        'data-slot' => 'highlighted-code-toolbar',
    ];
@endphp

@if ($slot->isNotEmpty() || $showCopy || $showCollapse)
    <div {{ $attributes->merge($attrs)->class(['flex items-center px-3 py-1.5 min-h-9']) }}
        style="background-color: #17191e">
        @if ($slot->isNotEmpty())
            <x-ui.typography.muted
                class="flex w-full items-center gap-2 text-zinc-400">
                {{ $slot }}
            </x-ui.typography.muted>
        @endif

        @if ($showCollapse || $showCopy)
            <span class="ml-auto flex items-center gap-2"
                data-slot="code-actions">
                @if ($showCollapse)
                    <x-ui.button size="sm"
                        variant="ghost"
                        x-on:click="expanded = !expanded"
                        x-text="expanded ? 'Collapse' : 'Expand'" />
                @endif

                @if ($showCopy)
                    <x-ui.button
                        class="text-muted-foreground relative inline-flex size-7 items-center justify-center"
                        size="icon-sm"
                        variant="ghost"
                        x-bind:disabled="isCopied"
                        x-data="buttonCopyCode"
                        x-on:click="copy">
                        <span class="absolute"
                            x-show="!isCopied"
                            x-transition.opacity>
                            <x-ui.icon class="size-3.5"
                                name="copy" />
                        </span>

                        <span class="absolute"
                            x-show="isCopied"
                            x-transition.opacity>
                            <x-ui.icon class="size-3.5"
                                name="check" />
                        </span>
                    </x-ui.button>
                @endif
            </span>
        @endif
    </div>
@endif

@pushOnce('bladcn-scripts')
    <script>
        bladcnRegister('buttonCopyCode', () => ({
            isCopied: false,

            copy() {
                const wrapper = this.$el.closest(
                    '[data-slot="highlighted-code-content"], [data-slot="highlighted-code"]',
                );
                const codeBlock = wrapper?.querySelector(
                    '[data-slot="highlighted-code-block"]',
                );
                if (!codeBlock) return;

                const code = codeBlock.dataset.code;
                if (!code) return;

                if (navigator.clipboard?.writeText) {
                    navigator.clipboard.writeText(code);
                } else {
                    const textarea = document.createElement('textarea');
                    textarea.value = code;
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
    </script>
@endPushOnce
