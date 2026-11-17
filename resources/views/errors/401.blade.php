<x-layouts.error title="Autentikasi Diperlukan"
    description="Kamu perlu masuk ke akun SantriNexus untuk mengakses halaman ini.">
    <section class="relative mx-auto flex w-full max-w-2xl flex-col items-center text-center">
        <div
            class="relative mb-8 flex aspect-[16/9] w-full max-w-lg items-center justify-center overflow-hidden rounded-3xl border border-border/80 bg-surface/70 shadow-2xl shadow-cyan-950/10">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(34,211,238,0.12),transparent_65%)]"
                aria-hidden="true"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.045)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.045)_1px,transparent_1px)] bg-[size:32px_32px]"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-64 -translate-x-1/2 -translate-y-1/2 rounded-full border border-primary/10"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-48 -translate-x-1/2 -translate-y-1/2 rounded-full border border-primary/15"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-32 -translate-x-1/2 -translate-y-1/2 rounded-full border border-primary/20"
                aria-hidden="true"></div>
            <div class="relative flex items-center justify-center gap-3 sm:gap-5" aria-label="Error 401">
                <span
                    class="select-none text-8xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(34,211,238,0.12)] sm:text-9xl">4</span>
                <div
                    class="relative flex size-24 items-center justify-center rounded-3xl border border-primary/30 bg-primary/5 shadow-[0_0_45px_rgba(34,211,238,0.12)] sm:size-32">
                    <div class="absolute inset-2 rounded-2xl border border-dashed border-primary/30"></div>
                    <svg class="size-12 text-primary sm:size-16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <rect x="4" y="10" width="16" height="11" rx="2"></rect>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        <circle cx="12" cy="15" r="1"></circle>
                        <path d="M12 16v2"></path>
                    </svg>
                </div>
                <span
                    class="select-none text-8xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(34,211,238,0.12)] sm:text-9xl">1</span>
            </div>
            <div
                class="absolute left-6 top-6 flex items-center gap-2 rounded-full border border-border/80 bg-background/70 px-3 py-1.5 backdrop-blur-sm">
                <span class="size-1.5 rounded-full bg-warning shadow-[0_0_8px_rgba(251,191,36,0.8)]"></span>
                <span class="text-[10px] font-semibold tracking-[0.18em] text-muted">ERROR 401</span>
            </div>
            <div
                class="absolute bottom-6 right-6 hidden items-center gap-2 text-[10px] font-medium tracking-widest text-subtle sm:flex">
                <span class="size-1 rounded-full bg-primary"></span>
                AUTHENTICATION REQUIRED
            </div>
            <div class="absolute left-8 top-1/2 size-1 rounded-full bg-primary/70 shadow-[0_0_12px_rgba(34,211,238,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute bottom-10 left-1/4 size-1.5 rounded-full bg-secondary/70 shadow-[0_0_12px_rgba(56,189,248,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute right-10 top-1/3 size-1 rounded-full bg-accent/70 shadow-[0_0_12px_rgba(163,230,53,0.8)]"
                aria-hidden="true"></div>
        </div>
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-warning/20 bg-warning/5 px-3.5 py-2">
            <svg class="size-4 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="4" y="10" width="16" height="11" rx="2"></rect>
                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
            </svg>
            <span class="text-xs font-semibold tracking-wide text-warning">Autentikasi diperlukan</span>
        </div>
        <h1 class="max-w-xl text-balance text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">
            Masuk untuk melanjutkan.
        </h1>
        <p class="mt-4 max-w-lg text-pretty text-sm leading-7 text-muted sm:text-base">
            Halaman ini hanya dapat diakses oleh pengguna yang sudah masuk ke akun SantriNexus. Silakan masuk terlebih
            dahulu untuk melanjutkan.
        </p>
        <div class="mt-8 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
            <a href="{{ route('login') }}"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-background shadow-lg shadow-primary/15 transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-bright hover:shadow-primary/25 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <path d="m10 17 5-5-5-5"></path>
                    <path d="M15 12H3"></path>
                </svg>
                Masuk ke Akun
            </a>
            <a href="{{ route('home') }}"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground transition duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 10 9-7 9 7"></path>
                    <path d="M5 9v11h14V9"></path>
                    <path d="M9 20v-6h6v6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
        <div class="mt-12 flex w-full max-w-sm items-center gap-4">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-border"></div>
            <span class="text-[10px] font-semibold tracking-[0.2em] text-subtle">SANTRINEXUS</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-border"></div>
        </div>
        <p class="mt-4 text-xs leading-6 text-subtle">
            Belum memiliki akun?
            <a href="{{ route('register') }}"
                class="font-semibold text-primary transition hover:text-cyan-bright focus-visible:rounded-sm">Daftar
                sekarang</a>
        </p>
    </section>
</x-layouts.error>
