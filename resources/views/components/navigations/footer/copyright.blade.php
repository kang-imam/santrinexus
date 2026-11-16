@php
    $startYear = (int) config('company.legal.copyright_year', config('company.founded_year', date('Y')));
    $currentYear = (int) date('Y');
@endphp
<div class="flex flex-col gap-5 py-6 sm:flex-row sm:items-center sm:justify-between">
    <div class="space-y-1.5 text-center sm:text-left">
        <p class="text-xs text-subtle">
            &copy; {{ $startYear }}{{ $currentYear > $startYear ? '–' . $currentYear : '' }}
            <span
                class="font-semibold text-muted">{{ config('company.legal.copyright', config('company.legal_name')) }}</span>.
            All rights reserved.
        </p>
        <p class="text-xs text-subtle">
            {{ config('company.name') }} is a digital infrastructure and technology ecosystem.
        </p>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 sm:justify-end">
        @if (config('company.legal.privacy_url'))
            <a href="{{ config('company.legal.privacy_url') }}"
                class="text-xs text-subtle transition-colors hover:text-primary">Privacy Policy</a>
        @endif
        @if (config('company.legal.terms_url'))
            <a href="{{ config('company.legal.terms_url') }}"
                class="text-xs text-subtle transition-colors hover:text-primary">Terms & Conditions</a>
        @endif
        <span class="hidden h-1 w-1 rounded-full bg-border-bright sm:block"></span>
        <span class="text-xs font-medium text-muted">{{ config('company.brand.short_tagline') }}</span>
    </div>
</div>
