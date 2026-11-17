@php
    $currentYear = now(config('company.timezone', config('app.timezone', 'UTC')))->year;
    $foundedYear = (int) config('company.founded_year', $currentYear);
    $startYear = (int) config('company.legal.copyright_year', $foundedYear);
    $startYear = $startYear > 0 && $startYear <= $currentYear ? $startYear : $currentYear;
    $copyrightPeriod = $startYear < $currentYear ? $startYear . '–' . $currentYear : (string) $currentYear;
    $companyName = config('company.brand.name', config('company.name', config('app.name')));
    $shortName = config('company.short_name', $companyName);
    $copyrightHolder = config(
        'company.metadata.copyright_holder',
        config('company.legal.copyright_holder', config('company.legal_name', $companyName)),
    );
    $tagline = config('company.brand.short_tagline', config('company.brand.tagline', ''));
    $logo = config('company.brand.logo', []);
    $logo = is_array($logo) ? $logo : [];
    $logoPath = $logo['primary_path'] ?? ($logo['primary'] ?? '');
    $logoPath = is_string($logoPath) ? ltrim(trim($logoPath), '/') : '';
    $logoCandidates = array_filter([
        $logoPath,
        is_string($logo['primary'] ?? null) ? ltrim(trim($logo['primary']), '/') : '',
        is_string($logo['icon_path'] ?? null) ? ltrim(trim($logo['icon_path']), '/') : '',
        is_string($logo['icon'] ?? null) ? ltrim(trim($logo['icon']), '/') : '',
    ]);
    $logoPath = collect($logoCandidates)->first(fn($path) => is_file(public_path($path)));
    $logoAlt =
        is_string($logo['alt'] ?? null) && trim($logo['alt']) !== '' ? trim($logo['alt']) : 'Logo ' . $companyName;
    $companyUrl = config('company.url', config('app.url', ''));
    $hasCompanyUrl = false;
    if (
        is_string($companyUrl) &&
        trim($companyUrl) !== '' &&
        preg_match('/[\x00-\x20\x7F\\\\]/', $companyUrl) !== 1 &&
        filter_var(trim($companyUrl), FILTER_VALIDATE_URL) !== false
    ) {
        $companyScheme = strtolower((string) parse_url(trim($companyUrl), PHP_URL_SCHEME));
        $companyHost = (string) parse_url(trim($companyUrl), PHP_URL_HOST);
        $hasCompanyUrl = in_array($companyScheme, ['http', 'https'], true) && $companyHost !== '';
    }
    $legalDocuments = config('company.legal.documents', []);
    $legalDocuments = is_array($legalDocuments) ? $legalDocuments : [];
    $legalDefinitions = [
        ['key' => 'terms', 'label' => 'Ketentuan Layanan'],
        ['key' => 'privacy', 'label' => 'Privasi'],
        ['key' => 'cookies', 'label' => 'Kebijakan Cookie'],
        ['key' => 'disclaimer', 'label' => 'Disclaimer'],
        ['key' => 'copyright', 'label' => 'Hak Cipta'],
    ];
    $legalLinks = collect($legalDefinitions)
        ->map(function (array $item) use ($legalDocuments) {
            $routeName = $legalDocuments[$item['key']] ?? '';
            if (
                !is_string($routeName) ||
                trim($routeName) === '' ||
                !\Illuminate\Support\Facades\Route::has($routeName)
            ) {
                return null;
            }
            return [
                'key' => $item['key'],
                'label' => $item['label'],
                'url' => route($routeName),
            ];
        })
        ->filter()
        ->values();
    $status = strtolower((string) config('company.metadata.status', 'operational'));
    $statusDefinitions = [
        'operational' => ['label' => 'Operational', 'color' => 'bg-emerald-400'],
        'maintenance' => ['label' => 'Maintenance', 'color' => 'bg-amber-400'],
        'development' => ['label' => 'Development', 'color' => 'bg-sky-400'],
        'inactive' => ['label' => 'Inactive', 'color' => 'bg-slate-400'],
    ];
    $statusInfo = $statusDefinitions[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-slate-400'];
    $country = config('company.metadata.country', 'Indonesia');
@endphp
<div class="relative border-t border-white/[0.07]">
    <div class="flex flex-col gap-7 py-7 sm:py-8 lg:flex-row lg:items-center lg:justify-between lg:gap-8">
        <div class="flex min-w-0 flex-col items-center gap-3 text-center sm:flex-row sm:items-center sm:text-left">
            <a href="{{ $hasCompanyUrl ? trim($companyUrl) : url('/') }}" aria-label="Beranda {{ $companyName }}"
                class="group relative flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-primary/20 bg-primary/[0.07] shadow-[0_0_24px_rgba(34,211,238,0.07)] transition-colors hover:border-primary/40 hover:bg-primary/[0.12]">
                <span class="absolute inset-0 bg-gradient-to-br from-primary/[0.12] to-transparent"
                    aria-hidden="true"></span>
                @if ($logoPath)
                    <img src="{{ asset($logoPath) }}" alt="{{ $logoAlt }}"
                        class="relative size-8 object-contain transition-transform duration-300 group-hover:scale-110"
                        loading="lazy">
                @else
                    <x-shared.icon name="heroicon-o-building-office-2" fallback="fa-solid fa-building"
                        class="relative size-6 text-primary" aria-hidden="true" />
                @endif
            </a>
            <div class="min-w-0">
                <p class="text-xs leading-6 text-muted">
                    <span>© {{ $copyrightPeriod }}</span>
                    @if ($hasCompanyUrl)
                        <a href="{{ trim($companyUrl) }}"
                            class="font-bold text-foreground transition-colors hover:text-primary">{{ $copyrightHolder }}</a>
                    @else
                        <span class="font-bold text-foreground">{{ $copyrightHolder }}</span>
                    @endif
                    <span class="text-subtle">. Hak cipta dilindungi.</span>
                </p>
                @if (filled($tagline))
                    <p class="mt-1 text-[10px] font-medium uppercase tracking-[0.16em] text-subtle">{{ $tagline }}
                    </p>
                @endif
                <p class="mt-1 text-[10px] text-subtle">{{ $shortName }} ·
                    {{ $country }}</p>
            </div>
        </div>
        <div class="flex min-w-0 flex-col items-center gap-5 sm:gap-4 lg:items-end">
            @if ($legalLinks->isNotEmpty())
                <nav aria-label="Dokumen dan informasi legal">
                    <ul class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3 lg:justify-end">
                        @foreach ($legalLinks as $link)
                            <li>
                                <a href="{{ $link['url'] }}"
                                    class="inline-flex items-center gap-1.5 text-[11px] font-medium text-muted transition-colors duration-200 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                                    {{ $link['label'] }}
                                    <x-shared.icon name="heroicon-o-arrow-up-right"
                                        fallback="fa-solid fa-arrow-up-right-from-square" class="size-3 text-subtle"
                                        aria-hidden="true" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3 lg:justify-end">
                <span
                    class="inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.16em] text-subtle">
                    <span class="size-1.5 rounded-full {{ $statusInfo['color'] }}" aria-hidden="true"></span>
                    {{ $statusInfo['label'] }}
                </span>
                <span class="hidden h-3.5 w-px bg-white/10 sm:block" aria-hidden="true"></span>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-medium text-subtle">
                    <x-shared.icon name="heroicon-o-shield-check" fallback="fa-solid fa-shield-halved"
                        class="size-3.5 text-primary" aria-hidden="true" />
                    Secure Digital Ecosystem
                </span>
            </div>
        </div>
    </div>
</div>
