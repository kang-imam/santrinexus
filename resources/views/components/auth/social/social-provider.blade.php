@props([
    'provider' => 'google',
    'name' => null,
    'icon' => null,
    'href' => null,
    'type' => 'button',
    'variant' => 'outline',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'fullWidth' => true,
    'showIcon' => true,
])
@php
    $providers = [
        'google' => [
            'name' => 'Google',
            'icon' => 'fab-google',
            'label' => 'Lanjutkan dengan Google',
        ],
        'github' => [
            'name' => 'GitHub',
            'icon' => 'fab-github',
            'label' => 'Lanjutkan dengan GitHub',
        ],
        'microsoft' => [
            'name' => 'Microsoft',
            'icon' => 'fab-microsoft',
            'label' => 'Lanjutkan dengan Microsoft',
        ],
        'facebook' => [
            'name' => 'Facebook',
            'icon' => 'fab-facebook',
            'label' => 'Lanjutkan dengan Facebook',
        ],
        'apple' => [
            'name' => 'Apple',
            'icon' => 'fab-apple',
            'label' => 'Lanjutkan dengan Apple',
        ],
    ];
    $providerKey = strtolower(trim(is_string($provider) ? $provider : 'google'));
    $providerData = $providers[$providerKey] ?? [
        'name' => ucfirst($providerKey ?: 'Provider'),
        'icon' => 'heroicon-o-shield-check',
        'label' => 'Lanjutkan dengan ' . ucfirst($providerKey ?: 'Provider'),
    ];
    $providerName = is_string($name) && trim($name) !== '' ? trim($name) : $providerData['name'];
    $providerIcon = is_string($icon) && trim($icon) !== '' ? trim($icon) : $providerData['icon'];
    $variants = [
        'outline' =>
            'border border-border bg-surface-raised text-foreground hover:border-primary/40 hover:bg-surface-hover',
        'secondary' => 'border border-transparent bg-surface-raised text-foreground hover:bg-surface-hover',
        'ghost' => 'border border-transparent bg-transparent text-foreground hover:bg-surface-hover',
        'primary' => 'border border-primary/30 bg-primary/10 text-primary hover:bg-primary/15',
    ];
    $sizes = [
        'sm' => 'min-h-10 px-3 text-xs',
        'md' => 'min-h-12 px-4 text-sm',
        'lg' => 'min-h-14 px-5 text-sm',
    ];
    $variantClass = $variants[$variant] ?? $variants['outline'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $isDisabled = $disabled || $loading;
    $buttonClass = [
        'inline-flex items-center justify-center gap-3 rounded-xl font-semibold transition duration-200',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary',
        'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        $variantClass,
        $sizeClass,
        $fullWidth ? 'w-full' : 'w-auto',
    ];
    $accessibleLabel = trim((string) $slot) !== '' ? trim((string) $slot) : $providerData['label'];
@endphp
@if (filled($href) && !$isDisabled)
    <a href="{{ $href }}" {{ $attributes->class($buttonClass) }} aria-label="{{ $accessibleLabel }}">
        @if ($showIcon)
            <x-shared.icon :name="$providerIcon" class="size-5 shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $slot->isNotEmpty() ? $slot : 'Lanjutkan dengan ' . $providerName }}</span>
    </a>
@else
    <button type="{{ $type === 'submit' ? 'submit' : 'button' }}" {{ $attributes->class($buttonClass) }}
        @disabled($isDisabled) @if ($loading) aria-busy="true" @endif
        aria-label="{{ $accessibleLabel }}">
        @if ($loading)
            <x-shared.icon name="heroicon-o-arrow-path" class="size-5 shrink-0 animate-spin" aria-hidden="true" />
        @elseif ($showIcon)
            <x-shared.icon :name="$providerIcon" class="size-5 shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $slot->isNotEmpty() ? $slot : 'Lanjutkan dengan ' . $providerName }}</span>
    </button>
@endif
