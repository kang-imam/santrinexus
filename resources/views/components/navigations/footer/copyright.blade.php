@php
    $currentYear = now()->year;
    $startYear = (int) config('company.legal.copyright_year', config('company.founded_year', $currentYear));
    $startYear = $startYear > 0 && $startYear <= $currentYear ? $startYear : $currentYear;
    $copyrightName = config('company.legal.copyright') ?: config('company.legal_name') ?: config('company.name');
    $shortName = config('company.short_name') ?: config('company.name');
    $shortTagline = config('company.brand.short_tagline');
    $copyrightPeriod = $startYear < $currentYear ? $startYear . '–' . $currentYear : (string) $currentYear;
@endphp
<div class="flex flex-col items-center justify-between gap-3 py-6 text-center sm:flex-row sm:text-left">
    <div class="min-w-0">
        <p class="text-xs leading-6 text-muted">
            © {{ $copyrightPeriod }} {{ $copyrightName }}. Hak cipta dilindungi.
        </p>
        @if (filled($shortTagline))
            <p class="mt-1 text-xs leading-5 text-subtle">
                {{ $shortTagline }}
            </p>
        @endif
    </div>
    <p class="shrink-0 text-xs font-medium text-muted">
        {{ $shortName }}
    </p>
</div>
