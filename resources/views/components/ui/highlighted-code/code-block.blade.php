@blaze(fold: true)

@use(Spatie\ShikiPhp\Shiki)

@props([
    'id' => null,
    'style' => null,
    'class' => null,
    'language' => 'html',
    'theme' => 'min-dark',
    'showNumbers' => true,
    'copyable' => false,
    'code' => null,
])

@php
    $content = filled($code) ? trim($code) : trim($slot);
    $highlightedCode = str_replace(
        ["\n", "\r"],
        '',
        Shiki::highlight($content, $language, $theme),
    );

    $presetClass = (new \AiluraCode\Bladcn\Support\ClassResolver())->add(
        'overflow-x-auto text-[13px] leading-5 [&_.shiki]:!bg-transparent [&_pre]:m-0 [&_pre]:bg-transparent [&_pre]:px-3 [&_pre]:py-2',
    );

    if ($showNumbers) {
        $presetClass->add('line-numbers');
    }

    $attrs = [
        'id' => $id,
        'style' => $style,
        'data-slot' => 'highlighted-code-block',
        'data-language' => $language,
        'data-theme' => $theme,
        'data-show-numbers' => $showNumbers ? 'true' : 'false',
    ];

    if ($copyable) {
        $attrs['data-code'] = $content;
    }
@endphp

<div {{ $attributes->merge($attrs)->class([$presetClass, $class]) }}>
    {!! $highlightedCode !!}
</div>
