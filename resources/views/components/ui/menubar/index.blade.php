@blaze(fold: false)
{{-- @see https://ui.shadcn.com/docs/components/menubar --}}

@props([
    'defaultRadioValue' => null,
    'checkboxes' => [],
    'style' => null,
    'class' => null,
])

@php
    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'flex h-9 items-center gap-1 rounded-md border bg-background p-1 shadow-xs',
    );

    $presetAttributes = [
        'role' => 'menubar',
        'data-slot' => 'menubar',
    ];

    if (filled($style)) {
        $presetAttributes['style'] = $style;
    }
@endphp

<div {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    x-data="bladcnMenubar({
        radioValue: @js($defaultRadioValue),
        checkboxes: @js($checkboxes),
    })"
    x-on:click.outside="closeMenus()"
    x-on:keydown.escape.window="closeMenus()">
    {{ $slot }}
</div>

@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnMenubar', (config = {}) => ({
                activeMenu: null,
                menuX: 0,
                menuY: 0,
                checkboxes: config.checkboxes ?? {},
                radioValue: config.radioValue ?? null,

                openMenu(value, event) {
                    if (this.activeMenu === value) {
                        this.closeMenus();

                        return;
                    }

                    this.activeMenu = value;

                    if (event?.currentTarget) {
                        const rect = event.currentTarget
                            .getBoundingClientRect();
                        this.menuX = rect.left;
                        this.menuY = rect.bottom + 8;
                    }
                },

                isMenuOpen(value) {
                    return this.activeMenu === value;
                },

                closeMenus() {
                    this.activeMenu = null;
                },

                toggleCheckbox(key) {
                    this.checkboxes[key] = !this.checkboxes[
                        key];
                },

                isCheckboxChecked(key) {
                    return Boolean(this.checkboxes[key]);
                },

                selectRadio(value) {
                    this.radioValue = value;
                },

                isRadioSelected(value) {
                    return this.radioValue === value;
                },
            }));
        });
    </script>
@endPushOnce
