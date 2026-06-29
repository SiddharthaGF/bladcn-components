@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/chart --}}

@props([
    'config' => [],
    'data' => [],
    'labelKey' => 'label',
    'valueKey' => 'value',
    'chartId' => null,
    'style' => null,
    'class' => null,
])

@php
    $chartId =
        $chartId ?? 'chart-' . \Illuminate\Support\Str::uuid()->toString();

    $colorRules = collect($config)
        ->map(function ($item, $key) use ($chartId) {
            $color = $item['color'] ?? null;

            if (!$color) {
                return null;
            }

            return "[data-chart={$chartId}] { --color-{$key}: {$color}; }";
        })
        ->filter()
        ->implode(' ');

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'relative flex aspect-video justify-center text-xs',
    );

    $presetAttributes = [
        'data-slot' => 'chart',
        'data-chart' => $chartId,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnChart({
        data: @js($data),
        config: @js($config),
        labelKey: @js($labelKey),
        valueKey: @js($valueKey),
    })">
    @if ($colorRules !== '')
        <style>
            {!! $colorRules !!}
        </style>
    @endif

    <div class="relative flex h-full w-full flex-col gap-3 p-4">
        <div
            class="border-border/50 relative flex flex-1 items-end gap-2 border-b border-l pb-6 pl-8 pr-2 pt-2">
            <template :key="index"
                x-for="(item, index) in data">
                <div class="absolute bottom-6 flex items-end justify-center"
                    x-bind:style="`left: ${barX(index)}; width: ${barWidth()}; height: calc(100% - 1.5rem);`">
                    <div class="w-full max-w-[48px] rounded-sm opacity-90 transition-all hover:opacity-100"
                        x-bind:style="`height: ${barHeight(item[valueKey])}; background-color: ${color(valueKey)};`"
                        x-on:mouseenter="showTooltip(item, $event)"
                        x-on:mouseleave="hideTooltip()"></div>
                </div>
            </template>

            <template :key="`label-${index}`"
                x-for="(item, index) in data">
                <div class="text-muted-foreground absolute bottom-0 text-center text-[10px]"
                    x-bind:style="`left: ${barX(index)}; width: ${barWidth()};`"
                    x-text="item[labelKey]"></div>
            </template>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4"
            data-slot="chart-legend">
            <div class="flex items-center gap-1.5">
                <span class="size-2 shrink-0 rounded-[2px]"
                    x-bind:style="`background-color: color(valueKey)`"></span>
                <span x-text="label(valueKey)"></span>
            </div>
        </div>
    </div>

    <div class="border-border/50 bg-background pointer-events-none fixed z-50 grid min-w-[8rem] gap-1.5 rounded-lg border px-2.5 py-1.5 text-xs shadow-xl"
        data-slot="chart-tooltip"
        x-bind:style="tooltip ? `left: ${tooltip.x + 12}px; top: ${tooltip.y + 12}px` : ''"
        x-cloak
        x-show="tooltip"
        x-transition>
        <div class="font-medium"
            x-text="tooltip?.label"></div>
        <div class="flex items-center gap-2">
            <span class="size-2.5 shrink-0 rounded-[2px]"
                x-bind:style="`background-color: ${tooltip?.color}`"></span>
            <span class="text-muted-foreground"
                x-text="label(valueKey)"></span>
            <span class="ml-auto font-mono font-medium"
                x-text="tooltip?.value"></span>
        </div>
    </div>

    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnChart', (config = {}) => ({
                data: config.data ?? [],
                config: config.config ?? {},
                labelKey: config.labelKey ?? 'label',
                valueKey: config.valueKey ?? 'value',
                tooltip: null,

                get maxValue() {
                    const values = this.data.map((item) =>
                        Number(item[this.valueKey] ?? 0)
                    );

                    return Math.max(...values, 1);
                },

                barHeight(value) {
                    const numeric = Number(value ?? 0);

                    return `${(numeric / this.maxValue) * 100}%`;
                },

                barX(index) {
                    const count = this.data.length || 1;
                    const width = 100 / count;

                    return `${index * width}%`;
                },

                barWidth() {
                    const count = this.data.length || 1;
                    const gap = Math.min(12, 80 / count);

                    return `calc(${100 / count}% - ${gap}px)`;
                },

                color(key) {
                    return this.config[key]?.color ??
                        'var(--chart-1)';
                },

                label(key) {
                    return this.config[key]?.label ?? key;
                },

                showTooltip(item, event) {
                    this.tooltip = {
                        label: item[this.labelKey],
                        value: item[this.valueKey],
                        color: this.color(this.valueKey),
                        x: event.clientX,
                        y: event.clientY,
                    };
                },

                hideTooltip() {
                    this.tooltip = null;
                },
            }));
        });
    </script>
@endPushOnce
