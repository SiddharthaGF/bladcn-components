# AGENTS.md

Guide for AI agents working on **bladcn-components**.

## What this repository is

Official registry of [shadcn/ui](https://ui.shadcn.com) components ported to **Blade + Alpine.js** for Laravel. Single source of truth for **CSS, JS, and Blade views** consumed by the `bladcn` CLI.

- **Repo:** https://github.com/ailuracode/bladcn-components
- **Composer package (runtime):** `ailuracode/bladcn` (`src/`)
- **npm package (tooling):** `@ailuracode/bladcn-components` (Prettier only)

This is not a full Laravel app — it is a component library/registry. Changes must stay compatible with host projects using `bladcn add`.

## Structure

```
bladcn-components/
├── app/Providers/BladcnServiceProvider.php   # local provider copy (development)
├── src/                                     # PHP code published as ailuracode/bladcn
│   ├── BladcnServiceProvider.php
│   └── Support/ClassResolver.php
├── resources/
│   ├── css/          # tokens, theme, base, sonner
│   ├── js/
│   │   ├── bladcn.js           # global helpers + scroll-area, copy, etc.
│   │   └── bladcn/carousel.js  # Embla carousel
│   └── views/
│       ├── components/ui/      # ~59 components (one folder each)
│       └── partials/bladcn-boot.blade.php
├── composer.json
├── package.json
└── .prettierrc
```

Each component lives in `resources/views/components/ui/<name>/` with partial files (`index.blade.php`, `trigger.blade.php`, etc.) and, when applicable, `dependencies.json`.

## Blade component conventions

### shadcn reference

Every root component includes a reference URL comment:

```blade
{{-- @see https://ui.shadcn.com/docs/components/button --}}
```

When modifying a component, consult the shadcn/ui docs and keep visual and API parity.

### `@blaze`

- `fold: false` on roots and containers with Alpine logic (`index.blade.php`, providers).
- `fold: true` on simple leaves without `x-data` (e.g. `button`, menu items).

Requires `livewire/blaze` in the host project.

### Props and classes

Standard pattern:

```blade
@props(['variant' => 'default', 'class' => null, ...])

@php
    $presetClass = new \AiluraCode\Bladcn\Support\ClassResolver()
        ->add('base-classes')
        ->add(match ($variant) { ... });

    $presetAttributes = ['data-slot' => 'button', ...];
@endphp

<x-ui.abstract :as-child="$asChild"
    {{ $attributes->merge($presetAttributes)->class([$presetClass, $class]) }}
    default-tag="button">
    {{ $slot }}
</x-ui.abstract>
```

Rules:

- Use `ClassResolver` for variants/sizes (do not concatenate strings manually).
- Merge with `$attributes->merge(...)->class([$presetClass, $class])`.
- Include `data-slot` on every component piece.
- Expose `style` and `class` as props when the component supports them.
- `asChild` delegates to `<x-ui.abstract>` + `AsChildSlot` from the `ailuracode/bladcn` package.

### Alpine.js

Inline scripts go in `index.blade.php` (or the group's root file) with:

```blade
@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            Alpine.data('bladcnName', (config = {}) => ({ ... }));
        });
    </script>
@endPushOnce
```

Global helpers (`bladcnOnAlpine`, `bladcnRegister`) are defined in `partials/bladcn-boot.blade.php` and mirrored in `resources/js/bladcn.js` for Vite.

- Register reusable factories with `bladcnRegister('name', () => ({ ... }))`.
- Pass PHP config to Alpine with `@js($variable)`.
- Shared JS logic across components goes in `resources/js/bladcn.js` or `resources/js/bladcn/*.js`.

### Icons

`<x-ui.icon name="check" />` resolves to `lucide-*` via `mallardduck/blade-lucide-icons`. The `name` prop is required.

## dependencies.json

The `bladcn add` CLI reads this file to install transitive dependencies:

```json
{
  "dependencies": ["abstract", "button", "icon"],
  "npm": ["embla-carousel", "embla-carousel-autoplay"]
}
```

- `dependencies`: other registry components (no `ui/` prefix).
- `npm`: optional npm packages the host must install.

When creating or modifying a component, update `dependencies.json` if it introduces new dependencies.

## CSS

| File                                         | Role                                        |
| -------------------------------------------- | ------------------------------------------- |
| `app.css`                                    | Tailwind 4 entry: imports, `@source`, theme |
| `bladcn-base.css`                            | Reset and base utilities                    |
| `bladcn-theme.css` / `bladcn-theme.dark.css` | shadcn tokens (light/dark)                  |
| `sonner.css`                                 | Sonner toast styles                         |

Use semantic tokens (`bg-primary`, `text-muted-foreground`, etc.), not hardcoded colors. Tailwind classes belong in Blade, not CSS, except for global tokens.

## CLI integration

```bash
bladcn init --registry ../bladcn-components
bladcn add button accordion
bladcn add --all
```

The default registry for `ailuracode/bladcn` points to `../bladcn-components`.

Host project requirements:

| Dependency                       | Purpose                                                         |
| -------------------------------- | --------------------------------------------------------------- |
| `livewire/blaze`                 | `@blaze`                                                        |
| `mallardduck/blade-lucide-icons` | `<x-ui.icon>`                                                   |
| `ailuracode/bladcn`              | `ClassResolver`, `Toast`, `AsChildSlot`, `@asChild`             |
| `resources/js/app.js`            | `import './bladcn';`                                            |
| `resources/css/app.css`          | Tailwind 4 + tokens                                             |
| Layout                           | `@include('partials.bladcn-boot')` + `@stack('bladcn-scripts')` |

## Code formatting

```bash
npm run format        # apply Prettier
npm run format:check  # verify
```

Configuration in `.prettierrc`:

- Blade: 4 spaces, `printWidth: 80`, single quotes.
- Plugin: `@shufo/prettier-plugin-blade`.
- HTML attributes wrapped with `wrapAttributes: "force"`.
- Tailwind classes sorted (`sortTailwindcssClasses: true`).
- Component prefixes: `x-,livewire:`.

Run `npm run format` after editing Blade or JS files.

## Rules for agents

### Do

- Keep changes minimal and focused on the requested component.
- Follow existing patterns from the closest component (e.g. `dialog` for overlays, `dropdown-menu` for menus).
- Preserve `data-slot`, accessibility (ARIA), and parity with shadcn/ui.
- Update `dependencies.json` when appropriate.
- Respect PHP 8.2+ (`declare(strict_types=1)`, strict typing in `src/`).

### Don't

- Do not turn this repo into a Laravel demo app.
- Do not add npm/PHP dependencies without a real need.
- Do not break the public props API used by CLI consumers.
- Do not set `parser: "blade"` globally in Prettier (only in `overrides`).
- Do not create commits, PRs, or edit README/docs unless explicitly asked.
- Do not modify `vendor/` or `node_modules/`.

### Adding a new component

1. Create a folder in `resources/views/components/ui/<name>/`.
2. Implement `index.blade.php` and sub-components following shadcn/ui.
3. Add `dependencies.json` with transitive dependencies.
4. If JS is required, use `@pushOnce('bladcn-scripts')` or extract to `resources/js/`.
5. If global CSS is required, extend files in `resources/css/`.
6. Format with `npm run format`.

## Useful commands

```bash
composer install          # PHP dependencies
npm install               # formatting dependencies
npm run format:check      # format lint
```

## License

MIT
