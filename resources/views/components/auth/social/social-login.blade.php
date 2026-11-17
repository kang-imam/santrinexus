@props([
    'providers' => ['google'],
    'title' => null,
    'description' => null,
    'layout' => 'vertical',
    'variant' => 'outline',
    'size' => 'md',
    'loadingProvider' => null,
    'disabled' => false,
    'fullWidth' => true,
    'showIcons' => true,
    'showTitle' => false,
])
@php
    $providerRoutes = [
        'google' => 'auth.google.redirect',
        'github' => 'auth.github.redirect',
        'microsoft' => 'auth.microsoft.redirect',
        'facebook' => 'auth.facebook.redirect',
        'apple' => 'auth.apple.redirect',
    ];
    $providerNames = [
        'google' => 'Google',
        'github' => 'GitHub',
        'microsoft' => 'Microsoft',
        'facebook' => 'Facebook',
        'apple' => 'Apple',
    ];
    $availableProviders = collect(is_array($providers) ? $providers : [])
        ->filter(fn($provider) => is_string($provider) && isset($providerRoutes[strtolower(trim($provider))]))
        ->map(fn($provider) => strtolower(trim($provider)))
        ->unique()
        ->filter(fn($provider) => \Illuminate\Support\Facades\Route::has($providerRoutes[$provider]))
        ->values();
    $layouts = [
        'vertical' => 'flex flex-col',
        'horizontal' => 'grid grid-cols-1 sm:grid-cols-2',
    ];
    $layoutClass = $layouts[$layout] ?? $layouts['vertical'];
    $title = is_string($title) ? trim($title) : null;
    $description = is_string($description) ? trim($description) : null;
    $loadingProvider = is_string($loadingProvider) ? strtolower(trim($loadingProvider)) : null;
@endphp
@if ($availableProviders->isNotEmpty())
    <div {{ $attributes->class(['w-full space-y-4']) }}>
        @if ($showTitle && ($title || $description))
            <div class="space-y-1 text-center">
                @if ($title)
                    <h2 class="text-sm font-semibold text-foreground">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="text-xs leading-5 text-muted">{{ $description }}</p>
                @endif
            </div>
        @endif
        <div class="{{ $layoutClass }} gap-3">
            @foreach ($availableProviders as $provider)
                <x-auth.social.social-provider :provider="$provider" :name="$providerNames[$provider]" :href="route($providerRoutes[$provider])" :variant="$variant"
                    :size="$size" :loading="$loadingProvider === $provider" :disabled="$disabled || ($loadingProvider !== null && $loadingProvider !== $provider)" :full-width="$fullWidth" :show-icon="$showIcons" />
            @endforeach
        </div>
    </div>
@endif
