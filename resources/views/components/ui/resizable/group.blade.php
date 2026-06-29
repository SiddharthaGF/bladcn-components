@blaze(fold: false)

@props([
    'orientation' => 'horizontal',
    'defaultSizes' => [50, 50],
    'minSize' => 10,
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add('group/resizable-panel-group flex h-full w-full')
        ->add($orientation === 'vertical' ? 'flex-col' : 'flex-row');

    $presetAttributes = [
        'data-slot' => 'resizable-panel-group',
        'data-orientation' => $orientation,
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnResizableGroup({
        orientation: @js($orientation),
        defaultSizes: @js($defaultSizes),
        minSize: @js($minSize),
    })"
    x-ref="group">
    {{ $slot }}
</div>
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnResizableGroup', (config = {}) => ({
                orientation: config.orientation ?? 'horizontal',
                sizes: [...(config.defaultSizes ?? [50, 50])],
                minSize: config.minSize ?? 10,
                draggingIndex: null,
                startPointer: 0,
                startSizes: [],

                startDrag(index, event) {
                    event.preventDefault();
                    this.draggingIndex = index;
                    this.startPointer =
                        this.orientation === 'horizontal' ?
                        event.clientX : event.clientY;
                    this.startSizes = [...this.sizes];
                    this.onPointerMove = this.onPointerMove
                        .bind(this);
                    this.onPointerUp = this.onPointerUp.bind(
                        this);
                    window.addEventListener('pointermove', this
                        .onPointerMove);
                    window.addEventListener('pointerup', this
                        .onPointerUp);
                },

                onPointerMove(event) {
                    if (this.draggingIndex === null) {
                        return;
                    }

                    const rect = this.$refs.group
                        .getBoundingClientRect();
                    const totalSize =
                        this.orientation === 'horizontal' ? rect
                        .width : rect.height;
                    const pointer =
                        this.orientation === 'horizontal' ?
                        event.clientX : event.clientY;
                    const deltaPercent = ((pointer - this
                        .startPointer) / totalSize) * 100;

                    const leftIndex = this.draggingIndex;
                    const rightIndex = this.draggingIndex + 1;
                    const nextLeft = this.startSizes[
                        leftIndex] + deltaPercent;
                    const nextRight = this.startSizes[
                        rightIndex] - deltaPercent;

                    if (nextLeft < this.minSize || nextRight <
                        this.minSize) {
                        return;
                    }

                    this.sizes[leftIndex] = nextLeft;
                    this.sizes[rightIndex] = nextRight;
                },

                onPointerUp() {
                    this.draggingIndex = null;
                    window.removeEventListener('pointermove',
                        this.onPointerMove);
                    window.removeEventListener('pointerup', this
                        .onPointerUp);
                },

                panelStyle(index) {
                    const size = this.sizes[index] ?? 0;

                    if (this.orientation === 'horizontal') {
                        return `flex: 0 0 ${size}%; min-width: 0;`;
                    }

                    return `flex: 0 0 ${size}%; min-height: 0;`;
                },
            }));
        });
    </script>
@endPushOnce
