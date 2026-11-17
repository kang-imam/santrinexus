@php
    $companyName = config('company.brand.name', config('company.name', config('app.name')));
    $legalName = config('company.legal_name', '');
    $tagline = config('company.brand.tagline', '');
    $shortTagline = config('company.brand.short_tagline', '');
    $description = config('company.profile.about', '') ?: config('company.description', '');
    $philosophy = config('company.brand.philosophy', []);
    $philosophy = is_array($philosophy) ? $philosophy : [];
    $email = config('company.contact.emails.general', '') ?: config('company.contact.primary_email', '');
    $whatsappNumber = config('company.contact.whatsapp.number', '');
    $whatsappUrl = config('company.contact.whatsapp.url', '');
    $address = config('company.contact.address.formatted', '');
    $mapsUrl = config('company.contact.address.maps_url', '') ?: config('company.contact.maps_url', '');
    $businessHours = config('company.contact.business_hours', []);
    $days = is_array($businessHours['days'] ?? null) ? $businessHours['days'] : [];
    $timezone = $businessHours['timezone'] ?? config('company.timezone', 'Asia/Jakarta');
    $holidayNote = $businessHours['holiday_note'] ?? '';
    $hasEmail = is_string($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    $isValidWebUrl = static function ($url): bool {
        if (!is_string($url) || trim($url) === '' || preg_match('/[\x00-\x20\x7F\\\\]/', $url) === 1) {
            return false;
        }
        if (filter_var(trim($url), FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $scheme = strtolower((string) parse_url(trim($url), PHP_URL_SCHEME));
        $host = (string) parse_url(trim($url), PHP_URL_HOST);
        return in_array($scheme, ['http', 'https'], true) && $host !== '';
    };
    $hasWhatsapp = $isValidWebUrl($whatsappUrl);
    $hasAddress = is_string($address) && trim($address) !== '';
    $hasMaps = $isValidWebUrl($mapsUrl);
    $philosophyItems = [
        [
            'key' => 'santri',
            'title' => 'Santri',
            'icon' => 'heroicon-o-academic-cap',
            'fallback' => 'fa-solid fa-graduation-cap',
        ],
        ['key' => 'nexus', 'title' => 'Nexus', 'icon' => 'heroicon-o-share', 'fallback' => 'fa-solid fa-share-nodes'],
        [
            'key' => 'meaning',
            'title' => 'Makna SantriNexus',
            'icon' => 'heroicon-o-light-bulb',
            'fallback' => 'fa-solid fa-lightbulb',
        ],
    ];
    $hasPhilosophy = collect($philosophyItems)->contains(
        fn($item) => is_string($philosophy[$item['key']] ?? null) && trim($philosophy[$item['key']]) !== '',
    );
    $formatTime = static fn($time) => is_string($time) && trim($time) !== '' ? trim($time) : '—';
@endphp
<section aria-label="Profil dan informasi perusahaan {{ $companyName }}"
    class="grid grid-cols-1 gap-12 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16 xl:gap-20">
    <div class="min-w-0">
        <div class="mb-7">
            <div class="mb-4 flex items-center gap-3">
                <span class="h-px w-7 bg-primary"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.24em] text-primary">Tentang Kami</span>
            </div>
            <h2 class="text-xl font-bold tracking-tight text-foreground">{{ $companyName }}</h2>
            @if (filled($tagline))
                <p class="mt-2 text-xs font-medium leading-6 text-primary">{{ $tagline }}</p>
            @elseif (filled($shortTagline))
                <p class="mt-2 text-xs font-medium leading-6 text-primary">{{ $shortTagline }}</p>
            @endif
            @if (filled($description))
                <p class="mt-4 max-w-xl text-sm leading-8 text-muted">{{ $description }}</p>
            @endif
        </div>
        @if ($hasPhilosophy)
            <div
                class="overflow-hidden rounded-2xl border border-white/[0.07] bg-gradient-to-br from-white/[0.035] to-white/[0.01]">
                <div class="flex items-center justify-between gap-4 border-b border-white/[0.07] px-5 py-4">
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Filosofi {{ $companyName }}</h3>
                        <p class="mt-1 text-xs text-subtle">Makna di balik identitas dan nilai kami</p>
                    </div>
                    <span
                        class="flex size-9 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary">
                        <x-shared.icon name="heroicon-o-light-bulb" fallback="fa-solid fa-lightbulb" class="size-5"
                            aria-hidden="true" />
                    </span>
                </div>
                <div>
                    @foreach ($philosophyItems as $item)
                        @if (is_string($philosophy[$item['key']] ?? null) && trim($philosophy[$item['key']]) !== '')
                            <div class="group flex gap-4 px-5 py-4 transition-colors hover:bg-white/[0.025]">
                                <span
                                    class="flex size-9 shrink-0 items-center justify-center rounded-xl border border-white/[0.07] bg-white/[0.035] text-primary transition-colors group-hover:border-primary/20 group-hover:bg-primary/[0.08]">
                                    <x-shared.icon :name="$item['icon']" :fallback="$item['fallback']" class="size-4"
                                        aria-hidden="true" />
                                </span>
                                <div class="min-w-0">
                                    <h4 class="mb-1.5 text-xs font-bold text-foreground">{{ $item['title'] }}</h4>
                                    <p class="text-xs leading-7 text-muted">{{ $philosophy[$item['key']] }}</p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="mx-5 border-t border-white/[0.05]"></div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="min-w-0 lg:border-l lg:border-white/[0.07] lg:pl-10 xl:pl-12">
        <div class="mb-7">
            <div class="mb-3 flex items-center gap-3">
                <span class="h-px w-7 bg-primary"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.24em] text-primary">Hubungi Kami</span>
            </div>
            <h2 class="text-lg font-bold tracking-tight text-foreground">Informasi Perusahaan</h2>
            <p class="mt-2 text-xs leading-6 text-muted">Temukan informasi dan kanal komunikasi resmi
                {{ $companyName }}.</p>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @if ($hasEmail)
                <a href="mailto:{{ trim($email) }}"
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:bg-primary/[0.035] motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-shared.icon name="heroicon-o-envelope" fallback="fa-solid fa-envelope" class="size-5"
                            aria-hidden="true" />
                    </span>
                    <span class="min-w-0 pt-0.5">
                        <span class="mb-1.5 block text-xs font-semibold text-foreground">Email</span>
                        <span
                            class="block break-all text-xs leading-6 text-muted transition-colors group-hover:text-primary">{{ $email }}</span>
                    </span>
                </a>
            @endif
            @if ($hasWhatsapp)
                <a href="{{ trim($whatsappUrl) }}" target="_blank" rel="noopener noreferrer"
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:bg-primary/[0.035] motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-shared.icon name="fab-whatsapp" fallback="fa-brands fa-whatsapp" class="size-5"
                            aria-hidden="true" />
                    </span>
                    <span class="min-w-0 pt-0.5">
                        <span class="mb-1.5 block text-xs font-semibold text-foreground">WhatsApp</span>
                        <span
                            class="block break-words text-xs leading-6 text-muted transition-colors group-hover:text-primary">{{ filled($whatsappNumber) ? $whatsappNumber : 'Hubungi melalui WhatsApp' }}</span>
                    </span>
                </a>
            @endif
            @if ($hasAddress)
                <div
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-colors duration-300 hover:border-primary/25 hover:bg-primary/[0.035] sm:col-span-2">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-shared.icon name="heroicon-o-map-pin" fallback="fa-solid fa-location-dot" class="size-5"
                            aria-hidden="true" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="mb-1.5 text-xs font-semibold text-foreground">
                            {{ config('company.contact.address.label', 'Kantor Pusat') }}</h3>
                        <p class="text-xs leading-6 text-muted">{{ $address }}</p>
                        @if ($hasMaps)
                            <a href="{{ trim($mapsUrl) }}" target="_blank" rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-secondary">
                                Lihat lokasi
                                <x-shared.icon name="heroicon-o-arrow-up-right"
                                    fallback="fa-solid fa-arrow-up-right-from-square" class="size-3.5"
                                    aria-hidden="true" />
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if (count($days) > 0)
                <div
                    class="group min-w-0 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-colors duration-300 hover:border-primary/25 hover:bg-primary/[0.035] sm:col-span-2">
                    <div class="flex items-start gap-3.5">
                        <span
                            class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary">
                            <x-shared.icon name="heroicon-o-clock" fallback="fa-solid fa-clock" class="size-5"
                                aria-hidden="true" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="mb-3 text-xs font-semibold text-foreground">Jam Operasional</h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                                @foreach ($days as $day)
                                    @if (is_array($day))
                                        <div class="flex items-center justify-between gap-3 text-xs">
                                            <span
                                                class="text-muted">{{ $day['label'] ?? ucfirst((string) $loop->iteration) }}</span>
                                            @if ($day['closed'] ?? false)
                                                <span class="font-medium text-subtle">Tutup</span>
                                            @else
                                                <span
                                                    class="text-right font-medium tabular-nums text-foreground">{{ $formatTime($day['open'] ?? '') }}–{{ $formatTime($day['close'] ?? '') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @if (filled($holidayNote))
                                <p class="mt-3 border-t border-white/[0.06] pt-3 text-[11px] leading-5 text-subtle">
                                    {{ $holidayNote }}</p>
                            @endif
                            <p class="mt-2 text-[10px] text-subtle">Zona waktu: {{ $timezone }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="mt-8">
            <x-navigations.footer.partials.social-links />
        </div>
        @if (filled($legalName))
            <div class="mt-7 border-t border-white/[0.07] pt-5">
                <div class="flex items-center gap-2">
                    <x-shared.icon name="heroicon-o-building-office-2" fallback="fa-solid fa-building"
                        class="size-4 text-primary" aria-hidden="true" />
                    <p class="text-[10px] font-medium uppercase tracking-[0.16em] text-subtle">Badan Hukum</p>
                </div>
                <p class="mt-2 text-xs font-semibold tracking-wide text-muted">{{ $legalName }}</p>
            </div>
        @endif
    </div>
</section>
