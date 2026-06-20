@blaze(fold: true)

<div aria-hidden="true"
    class="flex shrink-0 cursor-default items-center justify-center py-1"
    data-slot="select-scroll-down-button"
    x-cloak
    x-on:pointercancel="stopScrollAuto()"
    x-on:pointerdown.prevent="startScrollAuto('down')"
    x-on:pointerleave="stopScrollAuto()"
    x-on:pointermove="startScrollAuto('down')"
    x-on:pointerup="stopScrollAuto()"
    x-show="canScrollDown">
    <x-ui.icon class="size-4"
        name="chevron-down" />
</div>
