@php
    $currentYear = now()->year;
    $startYear = (int) config('company.legal.copyright_year', config('company.founded_year', $currentYear));
    $startYear = $startYear > 0 && $startYear <= $currentYear ? $startYear : $currentYear;
    $copyrightName = config('company.legal.copyright') ?: config('company.legal_name') ?: config('company.name');
    $shortName = config('company.short_name') ?: config('company.name');
    $tagline = config('company.brand.tagline');
    $logo = config('company.brand.logo.primary', 'logo_santrinexus.svg');
    $logoPath = 'images/branding/' . ltrim($logo, '/');
    if (!is_file(public_path($logoPath))) {
        $logoPath = ltrim($logo, '/');
    }
    if (!is_file(public_path($logoPath))) {
        $logoPath = null;
    }
    $copyrightPeriod = $startYear < $currentYear ? $startYear . '–' . $currentYear : (string) $currentYear;
    $legalDocuments = config('company.legal.documents', []);
    $legalDefinitions = [
        ['key' => 'terms', 'label' => 'Ketentuan Layanan'],
        ['key' => 'privacy', 'label' => 'Privasi'],
        ['key' => 'cookies', 'label' => 'Cookie'],
    ];
    $legalLinks = collect($legalDefinitions)
        ->map(function ($item) use ($legalDocuments) {
            $routeName = $legalDocuments[$item['key']] ?? null;
            if (!is_string($routeName) || !\Illuminate\Support\Facades\Route::has($routeName)) {
                return null;
            }
            return [
                'label' => $item['label'],
                'url' => route($routeName),
            ];
        })
        ->filter()
        ->values();
    $companyUrl = config('company.url');
    $hasCompanyUrl =
        is_string($companyUrl) &&
        filter_var($companyUrl, FILTER_VALIDATE_URL) &&
        in_array(strtolower((string) parse_url($companyUrl, PHP_URL_SCHEME)), ['http', 'https'], true);
@endphp
<div class="relative flex flex-col gap-6 py-7 sm:py-8 lg:flex-row lg:items-center lg:justify-between lg:gap-8">
    <div class="flex min-w-0 flex-col items-center gap-3 text-center sm:flex-row sm:items-center sm:text-left">
        <div
            class="group relative flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-primary/20 bg-primary/[0.07] shadow-[0_0_24px_rgba(34,211,238,0.07)]">
            <span class="absolute inset-0 bg-gradient-to-br from-primary/[0.12] to-transparent opacity-70"
                aria-hidden="true"></span>
            @if ($logoPath)
                <img src="{{ asset($logoPath) }}" alt="{{ $shortName }}"
                    class="relative h-7 w-7 object-contain transition-transform duration-300 group-hover:scale-110"
                    loading="lazy">
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-xs leading-6 text-muted">
                © {{ $copyrightPeriod }}
                @if ($hasCompanyUrl)
                    <a href="{{ $companyUrl }}"
                        class="font-bold text-foreground transition-colors hover:text-primary">{{ $copyrightName }}</a>
                @else
                    <span class="font-bold text-foreground">{{ $copyrightName }}</span>
                @endif
                <span class="text-subtle">. Hak cipta dilindungi.</span>
            </p>
            @if (filled($tagline))
                <p class="mt-1 text-[10px] font-medium uppercase tracking-[0.16em] text-subtle">{{ $tagline }}</p>
            @endif
        </div>
    </div>
    <div class="flex flex-col items-center gap-4 sm:flex-row sm:flex-wrap sm:justify-center lg:justify-end">
        @if ($legalLinks->isNotEmpty())
            <nav aria-label="Informasi legal">
                <ul class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                    @foreach ($legalLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                                class="text-[11px] font-medium text-muted transition-colors duration-200 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif
        @if ($legalLinks->isNotEmpty())
            <span class="hidden h-4 w-px bg-white/10 lg:block" aria-hidden="true"></span>
        @endif
        <span class="inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-subtle">
            <span class="h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(34,211,238,0.65)]"
                aria-hidden="true"></span>
            {{ $shortName }}
        </span>
    </div>
</div>
