# bladcn-components

[shadcn/ui](https://ui.shadcn.com) components ported to **Blade + Alpine.js** for Laravel.

This repository is the **single source of truth** for CSS, JavaScript, and Blade views used by the [`bladcn`](https://github.com/ailuracode/bladcn-cli) CLI. Install only the pieces you need into your Laravel app — the same copy-and-own workflow as shadcn/ui, but for the Laravel stack.

## Features

- **59 UI components** aligned with shadcn/ui (buttons, dialogs, sidebar, data-table, sonner, etc.)
- **Blade + Alpine.js** — no React, no Livewire required for interactivity
- **Tailwind CSS 4** with shadcn design tokens (light + dark)
- **Composable API** — `<x-ui.button>`, `<x-ui.dialog>`, nested sub-components
- **`asChild` pattern** via `ailuracode/bladcn` for flexible markup
- **Transitive dependencies** — `bladcn add dialog` pulls in `abstract`, `button`, `icon`, etc.
- **Lucide icons** through `<x-ui.icon name="check" />`

## Components

| Category     | Components                                                                                                                                                                         |
| ------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Layout       | `sidebar`, `resizable`, `scroll-area`, `separator`, `aspect-ratio`                                                                                                                 |
| Forms        | `button`, `input`, `textarea`, `select`, `checkbox`, `radio-group`, `switch`, `slider`, `field`, `form`, `label`, `input-group`, `input-otp`, `toggle`, `toggle-group`, `calendar` |
| Overlays     | `dialog`, `alert-dialog`, `sheet`, `drawer`, `popover`, `hover-card`, `tooltip`, `dropdown-menu`, `context-menu`, `menubar`, `navigation-menu`                                     |
| Data display | `table`, `data-table`, `card`, `badge`, `avatar`, `skeleton`, `progress`, `chart`, `typography`, `kbd`                                                                             |
| Navigation   | `breadcrumb`, `pagination`, `tabs`, `accordion`, `collapsible`                                                                                                                     |
| Feedback     | `alert`, `sonner`, `spinner`                                                                                                                                                       |
| Advanced     | `command`, `command-block`, `combobox`, `carousel`, `highlighted-code`, `button-group`                                                                                             |
| Primitives   | `abstract`, `icon`                                                                                                                                                                 |

## How it works

```
bladcn-components/          ← registry (this repo)
        │
        ▼  bladcn add button
your-laravel-app/
├── resources/views/components/ui/button/   ← copied Blade files
├── resources/js/bladcn.js                  ← copied / merged JS
└── resources/css/…                         ← theme tokens
```

1. Clone this repo next to your Laravel app (or `bladcn-cli`).
2. Run `bladcn init` to configure the registry path.
3. Run `bladcn add <component>` to copy files and resolve dependencies.
4. Wire up CSS, JS, and layout in your host project (see below).

The default registry for `ailuracode/bladcn` is `../bladcn-components`.

## Quick start

### 1. Clone the registry

```bash
git clone https://github.com/ailuracode/bladcn-components.git
cd your-laravel-app
```

Place `bladcn-components` as a sibling directory:

```
projects/
├── your-laravel-app/
└── bladcn-components/
```

### 2. Install host dependencies

```bash
composer require ailuracode/bladcn livewire/blaze mallardduck/blade-lucide-icons
```

### 3. Initialize and add components

```bash
bladcn init --registry ../bladcn-components
bladcn add button accordion dialog
# or install everything:
bladcn add --all
```

### 4. Configure CSS (Tailwind 4)

Copy or merge the CSS entry from this registry into `resources/css/app.css`:

```css
@import "tailwindcss";
@import "tw-animate-css";
@import "./sonner.css";

@source '../views';
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';

@import "./bladcn-base.css";
@import "./bladcn-theme.css";
```

| File                    | Purpose                                                  |
| ----------------------- | -------------------------------------------------------- |
| `bladcn-base.css`       | Base reset and utilities                                 |
| `bladcn-theme.css`      | Light-mode shadcn tokens (`--primary`, `--radius`, etc.) |
| `bladcn-theme.dark.css` | Dark-mode overrides (import in your dark theme setup)    |
| `sonner.css`            | Toast styles for `<x-ui.sonner>`                         |

### 5. Configure JavaScript

Import the bladcn helpers from your Vite entry (`resources/js/app.js`):

```js
import "./bladcn";
```

`bladcn.js` registers shared Alpine factories (`bladcnScrollArea`, `buttonCopyCode`, carousel, etc.). Component-specific scripts are pushed via `@pushOnce('bladcn-scripts')` in each Blade file.

### 6. Layout

Include the boot partial **before** `@stack('bladcn-scripts')` in your layout:

```blade
@include('partials.bladcn-boot')

{{-- page content --}}

@stack('bladcn-scripts')
```

`bladcn-boot.blade.php` defines `bladcnOnAlpine` and `bladcnRegister` synchronously so inline component scripts work before the Vite bundle loads.

### 7. Add Sonner (optional)

Place `<x-ui.sonner />` once in your layout for toast notifications:

```blade
<body>
    {{ $slot }}
    <x-ui.sonner />
    @stack('bladcn-scripts')
</body>
```

Flash toasts use `AiluraCode\Bladcn\Support\Toast` from the `ailuracode/bladcn` package.

## CLI reference

```bash
bladcn init --registry ../bladcn-components   # set registry path
bladcn add button                              # add one component + deps
bladcn add button accordion dialog             # add multiple
bladcn add --all                               # add every component
```

### npm dependencies

Some components declare extra npm packages in `dependencies.json`. The CLI reports them; install manually:

```bash
npm install embla-carousel embla-carousel-autoplay   # carousel
```

Currently only `carousel` requires additional npm packages.

## Usage examples

### Button

```blade
<x-ui.button variant="outline" size="sm">Click me</x-ui.button>

<x-ui.button variant="destructive" as-child>
    <a href="/delete">Delete</a>
</x-ui.button>
```

### Accordion

```blade
<x-ui.accordion type="single" collapsible default-value="item-1">
    <x-ui.accordion.item value="item-1">
        <x-ui.accordion.trigger>Is it accessible?</x-ui.accordion.trigger>
        <x-ui.accordion.content>Yes. It follows WAI-ARIA patterns.</x-ui.accordion.content>
    </x-ui.accordion.item>
</x-ui.accordion>
```

### Dialog

```blade
<x-ui.dialog>
    <x-ui.dialog.trigger as-child>
        <x-ui.button variant="outline">Open</x-ui.button>
    </x-ui.dialog.trigger>
    <x-ui.dialog.content>
        <x-ui.dialog.header>
            <x-ui.dialog.title>Edit profile</x-ui.dialog.title>
            <x-ui.dialog.description>Make changes to your profile here.</x-ui.dialog.description>
        </x-ui.dialog.header>
        {{-- form fields --}}
        <x-ui.dialog.footer>
            <x-ui.dialog.close as-child>
                <x-ui.button variant="outline">Cancel</x-ui.button>
            </x-ui.dialog.close>
            <x-ui.button type="submit">Save</x-ui.button>
        </x-ui.dialog.footer>
    </x-ui.dialog.content>
</x-ui.dialog>
```

### Icons

```blade
<x-ui.icon name="check" class="size-4" />
<x-ui.icon name="chevron-down" />
```

### Sidebar

```blade
<x-ui.sidebar.provider>
    <x-ui.sidebar>
        <x-ui.sidebar.header />
        <x-ui.sidebar.content>
            <x-ui.sidebar.menu>
                <x-ui.sidebar.menu-item>
                    <x-ui.sidebar.menu-button>Dashboard</x-ui.sidebar.menu-button>
                </x-ui.sidebar.menu-item>
            </x-ui.sidebar.menu>
        </x-ui.sidebar.content>
    </x-ui.sidebar>
    <x-ui.sidebar.inset>
        {{-- main content --}}
    </x-ui.sidebar.inset>
</x-ui.sidebar.provider>
```

## Project structure

```
bladcn-components/
├── src/                          # PHP runtime (published as ailuracode/bladcn)
│   ├── BladcnServiceProvider.php
│   └── Support/ClassResolver.php
├── resources/
│   ├── css/
│   │   ├── app.css               # Tailwind 4 entry
│   │   ├── bladcn-base.css
│   │   ├── bladcn-theme.css
│   │   ├── bladcn-theme.dark.css
│   │   └── sonner.css
│   ├── js/
│   │   ├── bladcn.js             # global Alpine helpers
│   │   └── bladcn/carousel.js    # Embla carousel
│   └── views/
│       ├── components/ui/        # one folder per component
│       │   └── button/
│       │       ├── index.blade.php
│       │       └── dependencies.json
│       └── partials/
│           └── bladcn-boot.blade.php
├── composer.json                 # ailuracode/bladcn
├── package.json                  # formatting tooling
└── .prettierrc
```

Each component folder may contain multiple Blade partials (`trigger.blade.php`, `content.blade.php`, …) and a `dependencies.json` that declares registry and npm dependencies.

## Host project requirements

| Dependency                       | Purpose                                                         |
| -------------------------------- | --------------------------------------------------------------- |
| PHP 8.2+                         | Match expressions, typed properties                             |
| Laravel 12+                      | Blade components, Vite                                          |
| Tailwind CSS 4                   | Utility classes and `@source`                                   |
| `ailuracode/bladcn`              | `ClassResolver`, `AsChildSlot`, `Toast`, `@asChild`             |
| `livewire/blaze`                 | `@blaze(fold: …)` directive for Blade optimization              |
| `mallardduck/blade-lucide-icons` | Lucide icons for `<x-ui.icon>`                                  |
| Alpine.js                        | Bundled with Livewire 3 / included in your JS stack             |
| `resources/js/app.js`            | `import './bladcn';`                                            |
| `resources/css/app.css`          | Tailwind 4 + bladcn theme imports                               |
| Layout                           | `@include('partials.bladcn-boot')` + `@stack('bladcn-scripts')` |

## Conventions

Components follow a consistent pattern across the registry:

- **`@blaze`** — `fold: false` on Alpine roots, `fold: true` on leaf partials
- **`ClassResolver`** — variant/size classes via fluent `->add()` chains
- **`$attributes->merge()->class([$presetClass, $class])`** — prop + attribute merging
- **`data-slot`** — on every component piece for styling and testing
- **`asChild`** — render as child element via `<x-ui.abstract>` + `AsChildSlot`
- **Alpine scripts** — `@pushOnce('bladcn-scripts')` in root Blade files
- **shadcn reference** — `{{-- @see https://ui.shadcn.com/docs/components/… --}}` in each component

See [AGENTS.md](./AGENTS.md) for detailed contributor and AI-agent guidelines.

## Development

This repo is a component registry, not a runnable Laravel app. To work on it locally:

```bash
composer install
npm install
npm run format        # apply Prettier
npm run format:check  # verify formatting
```

Blade files are formatted with `@shufo/prettier-plugin-blade` (4-space indent, sorted Tailwind classes, `printWidth: 80`).

### Adding a component

1. Create `resources/views/components/ui/<name>/`.
2. Implement Blade partials following the [shadcn/ui docs](https://ui.shadcn.com/docs/components).
3. Add `dependencies.json` for transitive registry/npm deps.
4. Add Alpine scripts via `@pushOnce` or extract shared logic to `resources/js/`.
5. Run `npm run format`.

## Packages

| Package                         | Registry                             |
| ------------------------------- | ------------------------------------ |
| `ailuracode/bladcn`             | Composer — PHP runtime helpers       |
| `@ailuracode/bladcn-components` | npm — formatting tooling (this repo) |

## License

MIT
