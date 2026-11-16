@php
    $companyName = config('company.name', config('app.name'));
    $legalName = config('company.legal_name');
    $tagline = config('company.brand.tagline');
    $shortTagline = config('company.brand.short_tagline');
    $description = config('company.profile.about') ?: config('company.description');
    $philosophy = config('company.brand.philosophy');
    $email = config('company.contact.emails.general') ?: config('company.contact.email');
    $whatsappNumber = config('company.contact.whatsapp.number');
    $whatsappUrl = config('company.contact.whatsapp.url');
    $address = config('company.contact.address.formatted');
    $mapsUrl = config('company.contact.maps_url');
    $hours = config('company.contact.hours');
    $hasEmail = is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    $hasWhatsapp =
        is_string($whatsappUrl) &&
        filter_var($whatsappUrl, FILTER_VALIDATE_URL) &&
        in_array(strtolower((string) parse_url($whatsappUrl, PHP_URL_SCHEME)), ['http', 'https'], true);
    $hasAddress = is_string($address) && trim($address) !== '';
    $hasMaps = is_string($mapsUrl) && filter_var($mapsUrl, FILTER_VALIDATE_URL);
@endphp
<section aria-label="Profil dan informasi perusahaan" class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-16">
    <div class="min-w-0">
        <div class="max-w-xl">
            <div class="mb-6">
                <x-partials.brand />
            </div>
            <h2 class="mb-3 text-sm font-bold tracking-wide text-foreground">
                Tentang {{ $companyName }}
            </h2>
            @if (filled($tagline) || filled($shortTagline))
                <p class="mb-4 text-sm font-semibold leading-6 text-primary">
                    {{ $tagline ?: $shortTagline }}
                </p>
            @endif
            @if (filled($description))
                <p class="text-sm leading-7 text-muted">
                    {{ $description }}
                </p>
            @endif
            @if (filled($philosophy))
                <details class="group mt-5">
                    <summary
                        class="inline-flex cursor-pointer list-none items-center gap-2 text-xs font-semibold text-primary transition-colors hover:text-primary/75 focus-visible:rounded-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        Filosofi SantriNexus
                        <x-heroicon-o-chevron-down
                            class="h-3.5 w-3.5 transition-transform duration-200 group-open:rotate-180"
                            aria-hidden="true" />
                    </summary>
                    <p class="mt-3 text-sm leading-7 text-muted">
                        {{ $philosophy }}
                    </p>
                </details>
            @endif
        </div>
    </div>
    <div class="min-w-0 lg:border-l lg:border-border lg:pl-10">
        <h2 class="mb-6 text-sm font-bold tracking-wide text-foreground">
            Informasi Perusahaan
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
            @if ($hasEmail)
                <div class="flex min-w-0 items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-heroicon-o-envelope class="h-5 w-5" aria-hidden="true" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="mb-1 text-xs font-semibold text-foreground">Email</h3>
                        <a href="mailto:{{ $email }}"
                            class="break-all text-sm text-muted transition-colors hover:text-primary">
                            {{ $email }}
                        </a>
                    </div>
                </div>
            @endif
            @if ($hasWhatsapp)
                <div class="flex min-w-0 items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-heroicon-o-chat-bubble-left-right class="h-5 w-5" aria-hidden="true" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="mb-1 text-xs font-semibold text-foreground">WhatsApp</h3>
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                            class="text-sm text-muted transition-colors hover:text-primary">
                            {{ $whatsappNumber ?: 'Hubungi melalui WhatsApp' }}
                        </a>
                    </div>
                </div>
            @endif
            @if ($hasAddress)
                <div class="flex min-w-0 items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-heroicon-o-map-pin class="h-5 w-5" aria-hidden="true" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="mb-1 text-xs font-semibold text-foreground">Kantor Pusat</h3>
                        <p class="text-sm leading-6 text-muted">{{ $address }}</p>
                        @if ($hasMaps)
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:text-primary/75">
                                Lihat lokasi
                                <x-heroicon-o-arrow-up-right class="h-3.5 w-3.5" aria-hidden="true" />
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if (filled($hours['weekdays'] ?? null))
                <div class="flex min-w-0 items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-heroicon-o-clock class="h-5 w-5" aria-hidden="true" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="mb-1 text-xs font-semibold text-foreground">Jam Operasional</h3>
                        <p class="text-sm text-muted">{{ $hours['weekdays'] }}</p>
                        <p class="text-sm text-muted">{{ $hours['weekdays_open'] ?? '' }} -
                            {{ $hours['weekdays_close'] ?? '' }} WIB</p>
                        @if (filled($hours['saturday'] ?? null))
                            <p class="mt-1 text-xs text-subtle">Sabtu: {{ $hours['saturday'] }}</p>
                        @endif
                        @if (filled($hours['sunday'] ?? null))
                            <p class="text-xs text-subtle">Minggu: {{ $hours['sunday'] }}</p>
                        @endif
                    </div>
                </div>
            @endif
            <div class="mt-6">
                <x-navigations.footer.partials.social-links />
            </div>
            @if (filled($legalName))
                <p class="mt-5 text-xs leading-5 text-subtle">
                    {{ $legalName }}
                </p>
            @endif
        </div>
    </div>
</section>
