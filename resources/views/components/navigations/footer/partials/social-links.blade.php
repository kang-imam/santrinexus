@php
    $platforms = [
        'instagram' => ['label' => 'Instagram', 'icon' => 'fab-instagram', 'fallback' => 'fa-brands fa-instagram'],
        'facebook' => ['label' => 'Facebook', 'icon' => 'fab-facebook-f', 'fallback' => 'fa-brands fa-facebook-f'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fab-linkedin-in', 'fallback' => 'fa-brands fa-linkedin-in'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'fab-youtube', 'fallback' => 'fa-brands fa-youtube'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'fab-tiktok', 'fallback' => 'fa-brands fa-tiktok'],
        'github' => ['label' => 'GitHub', 'icon' => 'fab-github', 'fallback' => 'fa-brands fa-github'],
        'x' => ['label' => 'X', 'icon' => 'fab-x-twitter', 'fallback' => 'fa-brands fa-x-twitter'],
        'threads' => ['label' => 'Threads', 'icon' => 'fab-threads', 'fallback' => 'fa-brands fa-threads'],
    ];
    $socialLinks = collect($platforms)
        ->map(function (array $platform, string $key) {
            $social = config('company.socials.' . $key);
            if (!is_array($social) || !($social['enabled'] ?? true)) {
                return null;
            }
            $url = $social['url'] ?? '';
            if (!is_string($url)) {
                return null;
            }
            $url = trim($url);
            if (
                $url === '' ||
                preg_match('/[\x00-\x20\x7F\\\\]/', $url) === 1 ||
                filter_var($url, FILTER_VALIDATE_URL) === false
            ) {
                return null;
            }
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
                return null;
            }
            $username = $social['username'] ?? '';
            $icon = $social['icon'] ?? $platform['icon'];
            $fallback = $social['icon_fallback'] ?? $platform['fallback'];
            return [
                'key' => $key,
                'label' =>
                    is_string($social['label'] ?? null) && trim($social['label']) !== ''
                        ? trim($social['label'])
                        : $platform['label'],
                'icon' => is_string($icon) ? $icon : $platform['icon'],
                'fallback' => is_string($fallback) ? $fallback : $platform['fallback'],
                'url' => $url,
                'username' => is_string($username) ? trim($username) : '',
            ];
        })
        ->filter()
        ->unique(fn(array $social) => strtolower($social['url']))
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
                        aria-label="{{ $social['label'] }}{{ $social['username'] !== '' ? ' · ' . $social['username'] : '' }}"
                        title="{{ $social['label'] }}{{ $social['username'] !== '' ? ' · ' . $social['username'] : '' }}"
                        class="group flex size-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-slate-400 transition duration-200 hover:-translate-y-1 hover:border-primary/40 hover:bg-primary/10 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                        <x-shared.icon :name="$social['icon']" :fallback="$social['fallback']" class="size-[18px]" aria-hidden="true" />
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
