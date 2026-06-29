@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/command --}}

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex size-full flex-col overflow-hidden rounded-xl! bg-popover p-1 text-popover-foreground',
    );

    $presetAttributes = [
        'data-slot' => 'command',
        'role' => 'application',
        'tabindex' => '-1',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnCommand()"
    x-on:keydown="onKeydown($event)">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnCommand', () => ({
                search: '',
                highlightedValue: null,

                init() {
                    this.$watch('search', () => {
                        this.$nextTick(() => this
                            .highlightFirst());
                    });
                },

                visibleItems() {
                    return [
                        ...this.$root.querySelectorAll(
                            '[data-slot="command-item"]',
                        ),
                    ].filter((element) => {
                        return element.offsetParent !==
                            null && element.dataset
                            .disabled !== 'true';
                    });
                },

                highlightFirst() {
                    const items = this.visibleItems();

                    this.highlightedValue = items[0]?.dataset
                        ?.value ?? null;
                },

                isHighlighted(value) {
                    return this.highlightedValue === value;
                },

                showEmpty() {
                    if (!this.search.trim()) {
                        return false;
                    }

                    return this.visibleItems().length === 0;
                },

                onKeydown(event) {
                    const items = this.visibleItems();

                    if (items.length === 0) {
                        return;
                    }

                    const index = items.findIndex(
                        (element) => element.dataset
                        .value === this.highlightedValue,
                    );

                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        const next = items[Math.min(index + 1,
                            items.length - 1)];
                        this.highlightedValue = next?.dataset
                            ?.value ?? null;
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        const previous = items[Math.max(
                            index - 1, 0)];
                        this.highlightedValue = previous
                            ?.dataset?.value ?? null;
                    } else if (event.key === 'Enter' &&
                        this.highlightedValue) {
                        event.preventDefault();
                        items.find((element) => element
                                .dataset.value === this
                                .highlightedValue)
                            ?.click();
                    }
                },

                matchesQuery(text) {
                    if (!this.search.trim()) {
                        return true;
                    }

                    return text.toLowerCase().includes(this
                        .search.trim().toLowerCase());
                },
            }));
        });
    </script>
@endPushOnce
