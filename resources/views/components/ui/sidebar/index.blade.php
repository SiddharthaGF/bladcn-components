@blaze(fold: true)
{{-- @see https://ui.shadcn.com/docs/components/sidebar --}}

@props([
    'side' => 'left',
    'variant' => 'sidebar',
    'collapsible' => 'icon',
    'style' => null,
    'class' => null,
])

@php
    $isInsetOrFloating = in_array($variant, ['floating', 'inset'], true);

    $gapCollapsedClass = $isInsetOrFloating
        ? 'md:group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4)))]'
        : 'md:group-data-[collapsible=icon]:w-(--sidebar-width-icon)';

    $gapHtmlCollapsedClass = $isInsetOrFloating
        ? '[html[data-sidebar-collapsed]_&]:md:!w-[calc(var(--sidebar-width-icon)+(--spacing(4)))]'
        : '[html[data-sidebar-collapsed]_&]:md:!w-(--sidebar-width-icon)';

    $panelHtmlCollapsedClass = $isInsetOrFloating
        ? '[html[data-sidebar-collapsed]_&]:md:!w-[calc(var(--sidebar-width-icon)+(--spacing(4))+2px)]'
        : '[html[data-sidebar-collapsed]_&]:md:!w-(--sidebar-width-icon)';

    $panelSideClass =
        $side === 'left'
            ? 'start-0 group-data-[collapsible=offcanvas]:left-[calc(var(--sidebar-width)*-1)] md:left-0'
            : 'end-0 group-data-[collapsible=offcanvas]:right-[calc(var(--sidebar-width)*-1)] md:right-0';

    $panelVariantClass = $isInsetOrFloating
        ? 'md:p-2 md:group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4))+2px)]'
        : 'md:group-data-[collapsible=icon]:w-(--sidebar-width-icon) md:group-data-[side=left]:border-e md:group-data-[side=right]:border-s';

    $innerVariantClass =
        $variant === 'floating'
            ? 'group-data-[variant=floating]:border-sidebar-border group-data-[variant=floating]:rounded-lg group-data-[variant=floating]:border group-data-[variant=floating]:shadow-sm'
            : '';

    $overlayClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'fixed inset-0 z-40 bg-black/50 backdrop-blur-md transition-opacity ease-in-out md:hidden data-[state=open]:opacity-100 data-[state=open]:duration-500 data-[state=closed]:opacity-0 data-[state=closed]:duration-300',
    );

    $mobilePanelAnimationClass = (new \AiluraCode\Bladcn\Support\ClassResolver())
        ->add(
            'max-md:transition max-md:ease-in-out max-md:data-[state=closed]:animate-out max-md:data-[state=closed]:duration-300 max-md:data-[state=open]:animate-in max-md:data-[state=open]:duration-500',
        )
        ->add(
            match ($side) {
                'right'
                    => 'max-md:data-[state=closed]:slide-out-to-right max-md:data-[state=open]:slide-in-from-right',
                default
                    => 'max-md:data-[state=closed]:slide-out-to-left max-md:data-[state=open]:slide-in-from-left',
            },
        );
@endphp

<div @class($overlayClass)
    aria-hidden="true"
    data-state="closed"
    x-bind:aria-hidden="mobileAnimationState === 'closed'"
    x-bind:data-state="mobileAnimationState"
    x-cloak
    x-on:click="closeMobileSidebar()"
    x-show="mobilePresent && !$store.sidebar.matchesBreakpoint"></div>

<div {{ $attributes->class(['group peer text-sidebar-foreground', $class]) }}
    @if (filled($style)) style="{{ $style }}" @endif
    data-side="{{ $side }}"
    data-slot="sidebar"
    data-variant="{{ $variant }}"
    x-bind:data-collapsible="!expanded && $store.sidebar.matchesBreakpoint ?
        @js($collapsible) : null"
    x-bind:data-state="$store.sidebar.matchesBreakpoint ? (expanded ? 'expanded' : 'collapsed') : (
        $store.sidebar.visible ? 'open' : 'closed')">
    {{-- Desktop gap spacer --}}
    <div @class([
        'relative hidden h-svh w-(--sidebar-width) shrink-0 bg-transparent transition-[width] duration-200 ease-linear md:block',
        'group-data-[collapsible=offcanvas]:w-0',
        'group-data-[side=right]:rotate-180',
        $gapCollapsedClass,
        $gapHtmlCollapsedClass,
    ])
        data-slot="sidebar-gap"></div>

    {{-- Sidebar panel — mobile drawer slide + desktop width transition --}}
    <div @class([
        'fixed inset-y-0 z-50 flex h-svh w-(--sidebar-width) shrink-0 flex-col overflow-hidden transition-[left,right,width] duration-200 ease-linear md:z-10',
        'border-sidebar-border bg-sidebar md:flex',
        $side === 'left' ? 'border-e' : 'border-s',
        $panelSideClass,
        $panelVariantClass,
        $panelHtmlCollapsedClass,
        $mobilePanelAnimationClass,
    ])
        data-slot="sidebar-container"
        data-state="closed"
        x-bind:aria-modal="!$store.sidebar.matchesBreakpoint ? 'true' : null"
        x-bind:class="{
            'invisible': !$store.sidebar.matchesBreakpoint &&
                mobileAnimationState ===
                'closed' && !
                mobileClosing,
        }"
        x-bind:data-state="!$store.sidebar.matchesBreakpoint ? mobileAnimationState : null"
        x-bind:role="!$store.sidebar.matchesBreakpoint ? 'dialog' : null"
        x-show="$store.sidebar.matchesBreakpoint || mobilePresent">
        <div @class([
            'flex h-full min-h-0 w-full flex-col gap-2 overflow-hidden',
            $innerVariantClass,
        ])
            data-sidebar="sidebar"
            data-slot="sidebar-inner"
            x-on:click="handleMobileNavClick($event)">
            {{ $slot }}
        </div>
    </div>
</div>
