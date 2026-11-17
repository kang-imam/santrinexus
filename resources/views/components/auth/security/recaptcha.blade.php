@props([
    'siteKey' => null,
    'theme' => 'dark',
    'size' => 'normal',
    'language' => 'id',
    'tabindex' => 0,
    'title' => 'Verifikasi Keamanan',
    'showTitle' => false,
    'showDescription' => true,
    'description' => 'Silakan selesaikan verifikasi untuk melanjutkan.',
    'id' => null,
])
@php
    $siteKey = $siteKey ?: config('services.recaptcha.site_key');
    $theme = in_array($theme, ['light', 'dark'], true) ? $theme : 'dark';
    $size = in_array($size, ['normal', 'compact'], true) ? $size : 'normal';
    $language = preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $language) ? $language : 'id';
    $componentId = $id ?: 'recaptcha-' . \Illuminate\Support\Str::uuid();
@endphp
@if (filled($siteKey))
    <div id="{{ $componentId }}" {{ $attributes->class(['w-full space-y-3']) }}>
        @if ($showTitle)
            <h3 class="text-sm font-semibold text-foreground">{{ $title }}</h3>
        @endif
        @if ($showDescription)
            <p class="text-xs leading-5 text-muted">{{ $description }}</p>
        @endif
        <div class="flex w-full justify-center overflow-x-auto">
            <div class="g-recaptcha" data-sitekey="{{ $siteKey }}" data-theme="{{ $theme }}"
                data-size="{{ $size }}" data-tabindex="{{ $tabindex }}"></div>
        </div>
        <p class="text-center text-xs text-muted">
            Dilindungi oleh Google reCAPTCHA.
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer"
                class="text-primary hover:text-secondary">Privasi</a>
            dan
            <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer"
                class="text-primary hover:text-secondary">Ketentuan</a>.
        </p>
        <noscript>
            <div class="rounded-lg border border-warning/30 bg-warning/10 p-3 text-xs text-warning">
                JavaScript diperlukan untuk menjalankan verifikasi keamanan ini.
            </div>
            <div>
                <iframe src="https://www.google.com/recaptcha/api/fallback?k={{ urlencode($siteKey) }}"
                    title="Verifikasi Google reCAPTCHA" width="302" height="422" frameborder="0"></iframe>
                <textarea name="g-recaptcha-response" class="sr-only" rows="3" cols="40"></textarea>
            </div>
        </noscript>
    </div>
    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js?hl={{ urlencode($language) }}" async defer></script>
        @endpush
    @endonce
@elseif (config('app.debug'))
    <div class="rounded-xl border border-warning/30 bg-warning/10 p-4 text-sm text-warning" role="alert">
        Google reCAPTCHA belum dikonfigurasi. Periksa RECAPTCHA_SITE_KEY pada file environment.
    </div>
@endif
