@php
    $companyName = config('company.name', config('app.name'));
    $legalName = config('company.legal_name');
    $tagline = config('company.brand.tagline');
    $shortTagline = config('company.brand.short_tagline');
    $description = config('company.profile.about') ?: config('company.description');
    $philosophy = config('company.brand.philosophy', []);
    $philosophy = is_array($philosophy) ? $philosophy : [];
    $email = config('company.contact.emails.general') ?: config('company.contact.primary_email');
    $whatsappNumber = config('company.contact.whatsapp.number');
    $whatsappUrl = config('company.contact.whatsapp.url');
    $address = config('company.contact.address.formatted');
    $mapsUrl = config('company.contact.address.maps_url') ?: config('company.contact.maps_url');
    $businessHours = config('company.contact.business_hours', []);
    $days = is_array($businessHours['days'] ?? null) ? $businessHours['days'] : [];
    $timezone = $businessHours['timezone'] ?? 'Asia/Jakarta';
    $holidayNote = $businessHours['holiday_note'] ?? null;
    $hasEmail = is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    $hasWhatsapp =
        is_string($whatsappUrl) &&
        filter_var($whatsappUrl, FILTER_VALIDATE_URL) &&
        in_array(strtolower((string) parse_url($whatsappUrl, PHP_URL_SCHEME)), ['http', 'https'], true);
    $hasAddress = is_string($address) && trim($address) !== '';
    $hasMaps =
        is_string($mapsUrl) &&
        filter_var($mapsUrl, FILTER_VALIDATE_URL) &&
        in_array(strtolower((string) parse_url($mapsUrl, PHP_URL_SCHEME)), ['http', 'https'], true);
    $philosophyItems = [
        ['key' => 'santri', 'title' => 'Santri', 'icon' => 'heroicon-o-academic-cap'],
        ['key' => 'nexus', 'title' => 'Nexus', 'icon' => 'heroicon-o-share'],
        ['key' => 'meaning', 'title' => 'Makna SantriNexus', 'icon' => 'heroicon-o-light-bulb'],
    ];
    $operatingDays = collect($days)->filter(fn($day) => is_array($day) && !($day['closed'] ?? false))->values();
    $closedDays = collect($days)->filter(fn($day) => is_array($day) && ($day['closed'] ?? false))->values();
    $formatTime = fn($time) => is_string($time) && trim($time) !== '' ? $time : null;
@endphp
<section aria-label="Profil dan informasi perusahaan"
    class="grid grid-cols-1 gap-12 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16 xl:gap-20">
    <div class="min-w-0">
        <div class="mb-7">
            <div class="mb-4 flex items-center gap-3">
                <span class="h-px w-7 bg-primary"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.24em] text-primary">Tentang Kami</span>
            </div>
            @if (filled($description))
                <p class="mt-4 max-w-xl text-sm leading-8 text-muted">{{ $description }}</p>
            @endif
        </div>
        @if (collect($philosophyItems)->contains(fn($item) => filled($philosophy[$item['key']] ?? null)))
            <div
                class="overflow-hidden rounded-2xl border border-white/[0.07] bg-gradient-to-br from-white/[0.035] to-white/[0.01]">
                <div class="flex items-center justify-between gap-4 border-b border-white/[0.07] px-5 py-4">
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Filosofi SantriNexus</h3>
                        <p class="mt-1 text-xs text-subtle">Makna di balik identitas kami</p>
                    </div>
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary">
                        <x-heroicon-o-light-bulb class="h-5 w-5" aria-hidden="true" />
                    </div>
                </div>
                <div class="space-y-0">
                    @foreach ($philosophyItems as $item)
                        @if (filled($philosophy[$item['key']] ?? null))
                            <div class="group flex gap-4 px-5 py-4 transition-colors hover:bg-white/[0.025]">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/[0.07] bg-white/[0.035] text-primary transition-colors group-hover:border-primary/20 group-hover:bg-primary/[0.08]">
                                    <x-dynamic-component :component="$item['icon']" class="h-4 w-4" aria-hidden="true" />
                                </div>
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
            <p class="mt-2 text-xs leading-6 text-muted">Temukan informasi dan kanal komunikasi resmi SantriNexus.</p>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @if ($hasEmail)
                <a href="mailto:{{ $email }}"
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:bg-primary/[0.035] motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-heroicon-o-envelope class="h-5 w-5" aria-hidden="true" />
                    </span>
                    <span class="min-w-0 pt-0.5">
                        <span class="mb-1.5 block text-xs font-semibold text-foreground">Email</span>
                        <span
                            class="block break-all text-xs leading-6 text-muted transition-colors group-hover:text-primary">{{ $email }}</span>
                    </span>
                </a>
            @endif
            @if ($hasWhatsapp)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:bg-primary/[0.035] motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-heroicon-o-chat-bubble-left-right class="h-5 w-5" aria-hidden="true" />
                    </span>
                    <span class="min-w-0 pt-0.5">
                        <span class="mb-1.5 block text-xs font-semibold text-foreground">WhatsApp</span>
                        <span
                            class="block break-words text-xs leading-6 text-muted transition-colors group-hover:text-primary">{{ $whatsappNumber ?: 'Hubungi melalui WhatsApp' }}</span>
                    </span>
                </a>
            @endif
            @if ($hasAddress)
                <div
                    class="group flex min-w-0 items-start gap-3.5 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-all duration-300 hover:border-primary/25 hover:bg-primary/[0.035] sm:col-span-2">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary transition-colors group-hover:bg-primary/15">
                        <x-heroicon-o-map-pin class="h-5 w-5" aria-hidden="true" />
                    </span>
                    <span class="min-w-0 pt-0.5">
                        <span class="mb-1.5 block text-xs font-semibold text-foreground">Kantor Pusat</span>
                        <span class="block text-xs leading-6 text-muted">{{ $address }}</span>
                        @if ($hasMaps)
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-secondary">
                                Lihat lokasi
                                <x-heroicon-o-arrow-up-right class="h-3.5 w-3.5" aria-hidden="true" />
                            </a>
                        @endif
                    </span>
                </div>
            @endif
            @if ($operatingDays->isNotEmpty())
                <div
                    class="group min-w-0 rounded-xl border border-white/[0.07] bg-white/[0.02] p-4 transition-colors duration-300 hover:border-primary/25 hover:bg-primary/[0.035] sm:col-span-2">
                    <div class="flex items-start gap-3.5">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-primary/15 bg-primary/[0.08] text-primary">
                            <x-heroicon-o-clock class="h-5 w-5" aria-hidden="true" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="mb-3 text-xs font-semibold text-foreground">Jam Operasional</h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                                @foreach ($days as $day)
                                    @if (is_array($day))
                                        <div class="flex items-center justify-between gap-3 text-xs">
                                            <span
                                                class="text-muted">{{ $day['label'] ?? ucfirst($loop->iteration) }}</span>
                                            @if ($day['closed'] ?? false)
                                                <span class="font-medium text-subtle">Tutup</span>
                                            @else
                                                <span
                                                    class="text-right font-medium tabular-nums text-foreground">{{ $formatTime($day['open'] ?? null) ?? '—' }}–{{ $formatTime($day['close'] ?? null) ?? '—' }}</span>
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
                <p class="text-[10px] font-medium uppercase tracking-[0.16em] text-subtle">Badan Hukum</p>
                <p class="mt-2 text-xs font-semibold tracking-wide text-muted">{{ $legalName }}</p>
            </div>
        @endif
    </div>
</section>
