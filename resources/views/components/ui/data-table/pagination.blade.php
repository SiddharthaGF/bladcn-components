@blaze(fold: true)

@props([
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex items-center justify-between px-2',
    );

    $presetAttributes = [
        'data-slot' => 'data-table-pagination',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}>
    <div class="text-muted-foreground flex-1 text-sm"
        x-text="`${showingFrom}-${showingTo} of ${filteredRows.length} row(s)`">
    </div>
    <x-ui.pagination>
        <x-ui.pagination.content>
            <x-ui.pagination.item>
                <x-ui.button size="sm"
                    type="button"
                    variant="outline"
                    x-bind:disabled="page <= 1"
                    x-on:click="previousPage()">
                    Previous
                </x-ui.button>
            </x-ui.pagination.item>
            <x-ui.pagination.item>
                <x-ui.button size="sm"
                    type="button"
                    variant="outline"
                    x-bind:disabled="page >= pageCount"
                    x-on:click="nextPage()">
                    Next
                </x-ui.button>
            </x-ui.pagination.item>
        </x-ui.pagination.content>
    </x-ui.pagination>
</div>
