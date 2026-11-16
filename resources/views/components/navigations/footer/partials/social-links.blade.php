@php
    $platforms = [
        ['key' => 'instagram', 'label' => 'Instagram', 'icon' => 'fab-instagram'],
        ['key' => 'facebook', 'label' => 'Facebook', 'icon' => 'fab-facebook-f'],
        ['key' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'fab-linkedin-in'],
        ['key' => 'youtube', 'label' => 'YouTube', 'icon' => 'fab-youtube'],
        ['key' => 'tiktok', 'label' => 'TikTok', 'icon' => 'fab-tiktok'],
        ['key' => 'github', 'label' => 'GitHub', 'icon' => 'fab-github'],
        ['key' => 'x', 'label' => 'X', 'icon' => 'fab-x-twitter'],
        ['key' => 'threads', 'label' => 'Threads', 'icon' => 'fab-threads'],
    ];
    $socialLinks = collect($platforms)
        ->map(function ($platform) {
            $social = config('company.socials.' . $platform['key']);
            if (!is_array($social)) {
                return null;
            }
            $url = $social['url'] ?? null;
            if (!is_string($url) || !filter_var(trim($url), FILTER_VALIDATE_URL)) {
                return null;
            }
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
                return null;
            }
            return [
                ...$platform,
                'url' => trim($url),
                'username' => is_string($social['username'] ?? null) ? trim($social['username']) : '',
            ];
        })
        ->filter()
        ->unique('url')
        ->values();
@endphp
@if ($socialLinks->isNotEmpty())
    <section aria-labelledby="footer-social-heading" class="mt-8">
        <div class="mb-4">
            <h3 id="footer-social-heading" class="text-sm font-semibold tracking-wide text-white">Ikuti Kami</h3>
            <p class="mt-1 text-xs text-slate-400">Terhubung dengan ekosistem SantriNexus</p>
        </div>
        <ul class="flex flex-wrap items-center gap-3">
            @foreach ($socialLinks as $social)
                <li>
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                        aria-label="{{ $social['label'] }}"
                        title="{{ $social['label'] }}{{ $social['username'] ? ' · ' . $social['username'] : '' }}"
                        class="group flex size-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-slate-400 transition duration-200 hover:-translate-y-1 hover:border-primary/40 hover:bg-primary/10 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                        <x-dynamic-component :component="$social['icon']" class="size-[18px]" />
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
