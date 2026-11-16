@php
    $platforms = [
        [
            'key' => 'instagram',
            'label' => 'Instagram',
            'icon' => 'fab-instagram',
        ],
        [
            'key' => 'facebook',
            'label' => 'Facebook',
            'icon' => 'fab-facebook-f',
        ],
        [
            'key' => 'linkedin',
            'label' => 'LinkedIn',
            'icon' => 'fab-linkedin-in',
        ],
        [
            'key' => 'youtube',
            'label' => 'YouTube',
            'icon' => 'fab-youtube',
        ],
        [
            'key' => 'tiktok',
            'label' => 'TikTok',
            'icon' => 'fab-tiktok',
        ],
        [
            'key' => 'github',
            'label' => 'GitHub',
            'icon' => 'fab-github',
        ],
        [
            'key' => 'x',
            'label' => 'X',
            'icon' => 'fab-x-twitter',
        ],
        [
            'key' => 'threads',
            'label' => 'Threads',
            'icon' => 'fab-threads',
        ],
    ];
    $socialLinks = collect($platforms)
        ->map(function ($platform) {
            $url = config('company.socials.' . $platform['key']);
            if (!is_string($url) || trim($url) === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }
            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            if (!in_array($scheme, ['http', 'https'], true)) {
                return null;
            }
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            if ($host === '') {
                return null;
            }
            return [...$platform, 'url' => $url];
        })
        ->filter()
        ->unique('url')
        ->values();
@endphp
@if ($socialLinks->isNotEmpty())
    <section aria-labelledby="footer-social-heading" class="min-w-0">
        <h3 id="footer-social-heading" class="mb-3 text-xs font-semibold tracking-wide text-foreground">
            Ikuti Kami
        </h3>
        <ul class="flex flex-wrap items-center gap-2" aria-label="Media sosial SantriNexus">
            @foreach ($socialLinks as $social)
                <li>
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                        aria-label="Kunjungi {{ $social['label'] }} SantriNexus" title="{{ $social['label'] }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-background text-muted transition-all duration-200 hover:border-primary/40 hover:bg-primary/5 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                        <x-dynamic-component :component="$social['icon']" class="h-4 w-4" aria-hidden="true" />
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
