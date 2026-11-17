<x-layouts.error title="Layanan Sedang Tidak Tersedia"
    description="SantriNexus sedang menjalani pemeliharaan atau mengalami gangguan sementara. Silakan coba kembali nanti.">
    <section class="relative mx-auto flex w-full max-w-2xl flex-col items-center text-center">
        <div
            class="relative mb-8 flex aspect-[16/9] w-full max-w-lg items-center justify-center overflow-hidden rounded-3xl border border-border/80 bg-surface/70 shadow-2xl shadow-amber-950/10">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(251,191,36,0.10),transparent_65%)]"
                aria-hidden="true"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.045)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.045)_1px,transparent_1px)] bg-[size:32px_32px]"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-64 -translate-x-1/2 -translate-y-1/2 rounded-full border border-warning/10"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-48 -translate-x-1/2 -translate-y-1/2 rounded-full border border-warning/15"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-32 -translate-x-1/2 -translate-y-1/2 rounded-full border border-warning/20"
                aria-hidden="true"></div>
            <div class="relative flex items-center justify-center gap-2 sm:gap-4" aria-label="Error 503">
                <span
                    class="select-none text-7xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(251,191,36,0.12)] sm:text-9xl">5</span>
                <div
                    class="relative flex size-24 items-center justify-center rounded-3xl border border-warning/30 bg-warning/5 shadow-[0_0_45px_rgba(251,191,36,0.12)] sm:size-32">
                    <div class="absolute inset-2 rounded-2xl border border-dashed border-warning/30"></div>
                    <svg class="size-12 text-warning sm:size-16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4l-3.1 3.1-3-3z"></path>
                        <path d="m14 5 5-2 2 2-2 5"></path>
                    </svg>
                </div>
                <span
                    class="select-none text-7xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(251,191,36,0.12)] sm:text-9xl">3</span>
            </div>
            <div
                class="absolute left-6 top-6 flex items-center gap-2 rounded-full border border-border/80 bg-background/70 px-3 py-1.5 backdrop-blur-sm">
                <span class="size-1.5 rounded-full bg-warning shadow-[0_0_8px_rgba(251,191,36,0.8)]"></span>
                <span class="text-[10px] font-semibold tracking-[0.18em] text-muted">ERROR 503</span>
            </div>
            <div
                class="absolute bottom-6 right-6 hidden items-center gap-2 text-[10px] font-medium tracking-widest text-subtle sm:flex">
                <span class="size-1 rounded-full bg-warning"></span>
                SERVICE UNAVAILABLE
            </div>
            <div class="absolute left-8 top-1/2 size-1 rounded-full bg-warning/70 shadow-[0_0_12px_rgba(251,191,36,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute bottom-10 left-1/4 size-1.5 rounded-full bg-secondary/70 shadow-[0_0_12px_rgba(56,189,248,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute right-10 top-1/3 size-1 rounded-full bg-accent/70 shadow-[0_0_12px_rgba(163,230,53,0.8)]"
                aria-hidden="true"></div>
        </div>
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-warning/20 bg-warning/5 px-3.5 py-2">
            <svg class="size-4 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8v4l3 2"></path>
                <circle cx="12" cy="12" r="9"></circle>
            </svg>
            <span class="text-xs font-semibold tracking-wide text-warning">Pemeliharaan sistem</span>
        </div>
        <h1 class="max-w-xl text-balance text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">
            Kami sedang melakukan pemeliharaan.
        </h1>
        <p class="mt-4 max-w-lg text-pretty text-sm leading-7 text-muted sm:text-base">
            SantriNexus sedang menjalani pemeliharaan sistem atau mengalami gangguan sementara. Beberapa layanan mungkin
            belum dapat diakses. Silakan kembali beberapa saat lagi.
        </p>
        <div
            class="mt-7 flex w-full max-w-sm items-center gap-3 rounded-2xl border border-warning/15 bg-warning/5 p-4 text-left">
            <div
                class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-warning/20 bg-warning/10">
                <svg class="size-5 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v3"></path>
                    <path d="M12 18v3"></path>
                    <path d="M3 12h3"></path>
                    <path d="M18 12h3"></path>
                    <circle cx="12" cy="12" r="6"></circle>
                    <path d="M12 9v3l2 1"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-foreground">Layanan tersedia kembali nanti</p>
                <p class="mt-1 text-xs leading-5 text-muted">Kami sedang berupaya memastikan sistem dapat digunakan
                    kembali dengan normal.</p>
            </div>
        </div>
        <div class="mt-8 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
            <button type="button" onclick="window.location.reload()"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-background shadow-lg shadow-primary/15 transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-bright hover:shadow-primary/25 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 7v5h-5"></path>
                    <path d="M4 17v-5h5"></path>
                    <path d="M5.6 9A7 7 0 0 1 18 6l2 6"></path>
                    <path d="M18.4 15A7 7 0 0 1 6 18l-2-6"></path>
                </svg>
                Coba Lagi
            </button>
            <a href="{{ route('contact.index') }}"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground transition duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                Hubungi Kami
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M7 17 17 7"></path>
                    <path d="M7 7h10v10"></path>
                </svg>
            </a>
        </div>
        <div class="mt-12 flex w-full max-w-sm items-center gap-4">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-border"></div>
            <span class="text-[10px] font-semibold tracking-[0.2em] text-subtle">SANTRINEXUS</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-border"></div>
        </div>
        <p class="mt-4 max-w-md text-xs leading-6 text-subtle">
            Terima kasih atas pengertian kamu selama proses pemeliharaan berlangsung.
        </p>
    </section>
</x-layouts.error>
