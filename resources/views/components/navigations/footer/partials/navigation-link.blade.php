@props([
    'href' => null,
    'label' => '',
    'icon' => null,
    'iconFallback' => null,
    'active' => false,
    'target' => null,
    'rel' => null,
    'badge' => null,
    'description' => null,
])
@php
    $label = is_scalar($label) ? trim((string) $label) : '';
    $href = is_string($href) ? trim($href) : '';
    $icon = is_string($icon) ? trim($icon) : '';
    $iconFallback = is_string($iconFallback) ? trim($iconFallback) : '';
    $badge = is_scalar($badge) ? trim((string) $badge) : '';
    $description = is_scalar($description) ? trim((string) $description) : '';
    $target = is_string($target) ? trim($target) : '';
    $rel = is_string($rel) ? trim($rel) : '';
    $isActive = filter_var($active, FILTER_VALIDATE_BOOLEAN);
    $hasLabel = $label !== '';
    $hasIcon = $icon !== '';
    $hasBadge = $badge !== '';
    $hasDescription = $description !== '';
    $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));
    $host = parse_url($href, PHP_URL_HOST);
    $isProtocolRelative = str_starts_with($href, '//');
    $hasControlCharacters = preg_match('/[\x00-\x20\x7F]/', $href) === 1;
    $hasBackslash = str_contains($href, '\\');
    $isAllowedScheme = in_array($scheme, ['', 'http', 'https', 'mailto', 'tel', 'sms'], true);
    $isValidUrl =
        $href !== '' &&
        !$hasControlCharacters &&
        !$hasBackslash &&
        $isAllowedScheme &&
        (!$isProtocolRelative || (is_string($host) && $host !== '')) &&
        (!in_array($scheme, ['http', 'https'], true) || (is_string($host) && $host !== ''));
    $hasUrl = $isValidUrl;
    $target = in_array($target, ['_self', '_blank', '_parent', '_top'], true) ? $target : null;
    $relTokens = preg_split('/\s+/', strtolower($rel)) ?: [];
    $relTokens = array_filter($relTokens, fn($token) => preg_match('/^[a-z][a-z0-9-]*$/', $token) === 1);
    if ($target === '_blank') {
        $relTokens = array_merge($relTokens, ['noopener', 'noreferrer']);
    }
    $rel = implode(' ', array_unique($relTokens));
    $rel = $rel !== '' ? $rel : null;
    $linkClasses = [
        'group inline-flex min-w-0 max-w-full items-center gap-2.5 rounded-lg py-1 text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background',
        'font-semibold text-primary' => $isActive,
        'font-medium text-muted hover:translate-x-1 hover:text-primary' => !$isActive && $hasUrl,
        'font-medium text-foreground' => !$isActive && !$hasUrl,
    ];
    $iconClasses = [
        'h-4 w-4 shrink-0 transition-colors duration-200',
        'text-primary' => $isActive,
        'text-subtle group-hover:text-primary' => !$isActive && $hasUrl,
        'text-subtle' => !$hasUrl,
    ];
    $descriptionId = $hasDescription ? 'footer-link-description-' . \Illuminate\Support\Str::uuid() : null;
@endphp
@if ($hasLabel)
    @if ($hasUrl)
        <a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif
            @if ($rel) rel="{{ $rel }}" @endif
            @if ($isActive) aria-current="page" @endif
            @if ($descriptionId) aria-describedby="{{ $descriptionId }}" @endif
            {{ $attributes->class($linkClasses) }}>
            @if ($hasIcon)
                <x-shared.icon :name="$icon" :fallback="$iconFallback" :class="$iconClasses" aria-hidden="true" />
            @else
                <span @class([
                    'h-1.5 w-1.5 shrink-0 rounded-full transition-all duration-200',
                    'bg-primary shadow-[0_0_8px_var(--color-primary)]' => $isActive,
                    'bg-subtle group-hover:bg-primary group-hover:shadow-[0_0_8px_var(--color-primary)]' => !$isActive,
                ]) aria-hidden="true"></span>
            @endif
            <span class="min-w-0 truncate">{{ $label }}</span>
            @if ($hasBadge)
                <span
                    class="ml-auto shrink-0 rounded-md border border-primary/15 bg-primary/[0.08] px-2 py-0.5 text-[10px] font-bold leading-4 text-primary transition-colors duration-200 group-hover:border-primary/30 group-hover:bg-primary/15">{{ $badge }}</span>
            @endif
            @if ($target === '_blank')
                <x-shared.icon name="heroicon-o-arrow-top-right-on-square"
                    fallback="fa-solid fa-arrow-up-right-from-square"
                    class="h-3.5 w-3.5 shrink-0 text-subtle transition-colors duration-200 group-hover:text-primary"
                    aria-hidden="true" />
                <span class="sr-only">(membuka tab baru)</span>
            @endif
        </a>
        @if ($hasDescription)
            <span id="{{ $descriptionId }}" class="sr-only">{{ $description }}</span>
        @endif
    @else
        <span {{ $attributes->class(array_merge($linkClasses, ['cursor-default'])) }}>
            @if ($hasIcon)
                <x-shared.icon :name="$icon" :fallback="$iconFallback" class="h-4 w-4 shrink-0 text-subtle"
                    aria-hidden="true" />
            @else
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-subtle" aria-hidden="true"></span>
            @endif
            <span class="min-w-0 truncate">{{ $label }}</span>
            @if ($hasBadge)
                <span
                    class="ml-auto shrink-0 rounded-md border border-primary/15 bg-primary/[0.08] px-2 py-0.5 text-[10px] font-bold leading-4 text-primary">{{ $badge }}</span>
            @endif
        </span>
    @endif
@endif
