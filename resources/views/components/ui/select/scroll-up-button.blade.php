@blaze(fold: true)

<div aria-hidden="true"
    class="flex shrink-0 cursor-default items-center justify-center py-1"
    data-slot="select-scroll-up-button"
    x-cloak
    x-on:pointercancel="stopScrollAuto()"
    x-on:pointerdown.prevent="startScrollAuto('up')"
    x-on:pointerleave="stopScrollAuto()"
    x-on:pointermove="startScrollAuto('up')"
    x-on:pointerup="stopScrollAuto()"
    x-show="canScrollUp">
    <x-ui.icon class="size-4"
        name="chevron-up" />
</div>
