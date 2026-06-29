@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/slider --}}

@props([
    'defaultValue' => 50,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'orientation' => 'horizontal',
    'name' => null,
    'disabled' => false,
    'style' => null,
    'class' => null,
])

@php
    $values = is_array($defaultValue)
        ? array_values($defaultValue)
        : [$defaultValue];
    $thumbCount = count($values);

    $isHorizontal = $orientation === 'horizontal';

    $rootClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'relative flex min-w-0 touch-none select-none data-[disabled]:opacity-50',
        )
        ->add(
            $isHorizontal
                ? 'w-full items-center'
                : 'w-auto flex-col items-center',
        );

    $trackClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('relative shrink-0 overflow-hidden rounded-full bg-muted')
        ->add(
            $isHorizontal ? 'mx-2 h-1 w-full grow' : 'my-2 min-h-0 w-1 flex-1',
        );

    $rangeClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('absolute bg-primary')
        ->add($isHorizontal ? 'top-0 h-full' : 'left-0 w-full');

    $thumbClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'absolute z-10 block size-3 shrink-0 rounded-full border border-muted-foreground bg-primary shadow-sm ring-ring/50 transition-[color,box-shadow] hover:ring-4 focus-visible:ring-4 focus-visible:outline-hidden active:cursor-grabbing disabled:pointer-events-none disabled:opacity-50',
        )
        ->add(
            $isHorizontal
                ? 'top-1/2 -translate-y-1/2'
                : 'left-1/2 -translate-x-1/2',
        );

    $presetAttributes = [
        'data-slot' => 'slider',
        'data-orientation' => $orientation,
        'data-disabled' => $disabled ? '' : null,
        'role' => 'group',
        'aria-orientation' => $orientation,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$rootClass, $class]) }}
    x-data="bladcnSlider({
        min: @js($min),
        max: @js($max),
        step: @js($step),
        disabled: @js($disabled),
        orientation: @js($orientation),
        defaultValue: @js($values),
    })">
    @if ($name && $thumbCount === 1)
        <input :name="@js($name)"
            type="hidden"
            x-bind:value="values[0]" />
    @endif

    <div @class($trackClass)
        data-orientation="{{ $orientation }}"
        data-slot="slider-track"
        x-on:pointerdown="onTrackPointerDown($event)"
        x-ref="track">
        <div @class($rangeClass)
            data-orientation="{{ $orientation }}"
            data-slot="slider-range"
            x-bind:style="rangeStyle()"></div>
    </div>

    @foreach ($values as $index => $initial)
        <div @class($thumbClass)
            data-index="{{ $index }}"
            data-orientation="{{ $orientation }}"
            data-slot="slider-thumb"
            role="slider"
            tabindex="{{ $disabled ? '-1' : '0' }}"
            x-bind:aria-disabled="disabled"
            x-bind:aria-valuemax="max"
            x-bind:aria-valuemin="min"
            x-bind:aria-valuenow="values[{{ $index }}]"
            x-bind:class="{ 'ring-4': dragging && activeThumb === {{ $index }} }"
            x-bind:style="thumbStyle({{ $index }})"
            x-on:pointerdown="onThumbPointerDown($event, {{ $index }})">
        </div>
    @endforeach
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnSlider', (config = {}) => ({
                min: config.min ?? 0,
                max: config.max ?? 100,
                step: config.step ?? 1,
                disabled: config.disabled ?? false,
                orientation: config.orientation ?? 'horizontal',
                values: [...(config.defaultValue ?? [50])],
                activeThumb: null,
                dragging: false,

                init() {
                    if (this.values.length === 0) {
                        this.values = [this.min];
                    }

                    this.sortValues();
                },

                sortValues() {
                    if (this.values.length > 1) {
                        this.values.sort((a, b) => a - b);
                    }
                },

                percent(index) {
                    const range = this.max - this.min;

                    if (range === 0) {
                        return 0;
                    }

                    return ((this.values[index] - this.min) /
                        range) * 100;
                },

                rangeStyle() {
                    const isVertical = this.orientation ===
                        'vertical';

                    if (this.values.length < 2) {
                        const size = `${this.percent(0)}%`;

                        return isVertical ? {
                            bottom: '0',
                            left: '0',
                            height: size,
                            width: '100%'
                        } : {
                            top: '0',
                            left: '0',
                            width: size,
                            height: '100%'
                        };
                    }

                    const start = `${this.percent(0)}%`;
                    const size =
                        `${this.percent(this.values.length - 1) - this.percent(0)}%`;

                    return isVertical ? {
                        bottom: start,
                        left: '0',
                        height: size,
                        width: '100%'
                    } : {
                        top: '0',
                        left: start,
                        width: size,
                        height: '100%'
                    };
                },

                thumbStyle(index) {
                    const isVertical = this.orientation ===
                        'vertical';
                    const position = `${this.percent(index)}%`;

                    if (isVertical) {
                        return {
                            bottom: `calc(0.5rem + (100% - 1rem) * ${this.percent(index)} / 100)`,
                        };
                    }

                    return {
                        left: position,
                    };
                },

                valueFromPointer(event) {
                    const track = this.$refs.track;
                    const rect = track.getBoundingClientRect();
                    const isVertical = this.orientation ===
                        'vertical';
                    const ratio = isVertical ?
                        1 - (event.clientY - rect.top) / rect
                        .height :
                        (event.clientX - rect.left) /
                        rect
                        .width;
                    let value = this.min + ratio * (this.max -
                        this.min);

                    value = Math.round(value / this.step) * this
                        .step;

                    return Math.max(this.min, Math.min(this.max,
                        value));
                },

                onTrackPointerDown(event) {
                    if (this.disabled) {
                        return;
                    }

                    event.preventDefault();

                    const value = this.valueFromPointer(event);

                    if (this.values.length === 1) {
                        this.values[0] = value;
                        this.startDrag(event, 0);

                        return;
                    }

                    const closestIndex = this.values.reduce(
                        (closest, current, index) => {
                            const distance = Math.abs(
                                current - value);

                            return distance < closest
                                .distance ? {
                                    index,
                                    distance
                                } :
                                closest;
                        }, {
                            index: 0,
                            distance: Infinity
                        },
                    ).index;

                    this.values[closestIndex] = value;
                    this.startDrag(event, closestIndex);
                },

                onThumbPointerDown(event, index) {
                    if (this.disabled) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    this.startDrag(event, index);
                },

                startDrag(event, index) {
                    this.activeThumb = index;
                    this.dragging = true;
                    this.onPointerMove = this.onPointerMove
                        .bind(this);
                    this.onPointerUp = this.onPointerUp.bind(
                        this);
                    window.addEventListener('pointermove', this
                        .onPointerMove);
                    window.addEventListener('pointerup', this
                        .onPointerUp);
                    this.updateValue(event);
                },

                onPointerMove(event) {
                    if (!this.dragging || this.activeThumb ===
                        null) {
                        return;
                    }

                    this.updateValue(event);
                },

                onPointerUp() {
                    this.dragging = false;
                    this.activeThumb = null;
                    this.sortValues();
                    this.emitChange();
                    window.removeEventListener('pointermove',
                        this.onPointerMove);
                    window.removeEventListener('pointerup', this
                        .onPointerUp);
                },

                updateValue(event) {
                    if (this.activeThumb === null) {
                        return;
                    }

                    this.values[this.activeThumb] = this
                        .valueFromPointer(event);
                    this.emitChange();
                },

                emitChange() {
                    this.$dispatch('slider-change', {
                        value: [...this.values],
                    });
                },
            }));
        });
    </script>
@endPushOnce
