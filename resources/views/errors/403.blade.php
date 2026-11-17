<x-layouts.error title="Akses Ditolak"
    description="Kamu tidak memiliki izin yang diperlukan untuk mengakses halaman ini.">
    <section class="relative mx-auto flex w-full max-w-2xl flex-col items-center text-center">
        <div
            class="relative mb-8 flex aspect-[16/9] w-full max-w-lg items-center justify-center overflow-hidden rounded-3xl border border-border/80 bg-surface/70 shadow-2xl shadow-rose-950/10">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(251,113,133,0.11),transparent_65%)]"
                aria-hidden="true"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(148,163,184,0.045)_1px,transparent_1px),linear-gradient(to_bottom,rgba(148,163,184,0.045)_1px,transparent_1px)] bg-[size:32px_32px]"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-64 -translate-x-1/2 -translate-y-1/2 rounded-full border border-danger/10"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-48 -translate-x-1/2 -translate-y-1/2 rounded-full border border-danger/15"
                aria-hidden="true"></div>
            <div class="absolute left-1/2 top-1/2 size-32 -translate-x-1/2 -translate-y-1/2 rounded-full border border-danger/20"
                aria-hidden="true"></div>
            <div class="relative flex items-center justify-center gap-3 sm:gap-5" aria-label="Error 403">
                <span
                    class="select-none text-8xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(251,113,133,0.12)] sm:text-9xl">4</span>
                <div
                    class="relative flex size-24 items-center justify-center rounded-3xl border border-danger/30 bg-danger/5 shadow-[0_0_45px_rgba(251,113,133,0.12)] sm:size-32">
                    <div class="absolute inset-2 rounded-2xl border border-dashed border-danger/30"></div>
                    <svg class="size-12 text-danger sm:size-16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3 20 6v5c0 5-3.4 8.2-8 10-4.6-1.8-8-5-8-10V6l8-3Z"></path>
                        <path d="m9 9 6 6"></path>
                        <path d="m15 9-6 6"></path>
                    </svg>
                </div>
                <span
                    class="select-none text-8xl font-extrabold tracking-tighter text-foreground/90 drop-shadow-[0_0_25px_rgba(251,113,133,0.12)] sm:text-9xl">3</span>
            </div>
            <div
                class="absolute left-6 top-6 flex items-center gap-2 rounded-full border border-border/80 bg-background/70 px-3 py-1.5 backdrop-blur-sm">
                <span class="size-1.5 rounded-full bg-danger shadow-[0_0_8px_rgba(251,113,133,0.8)]"></span>
                <span class="text-[10px] font-semibold tracking-[0.18em] text-muted">ERROR 403</span>
            </div>
            <div
                class="absolute bottom-6 right-6 hidden items-center gap-2 text-[10px] font-medium tracking-widest text-subtle sm:flex">
                <span class="size-1 rounded-full bg-danger"></span>
                ACCESS FORBIDDEN
            </div>
            <div class="absolute left-8 top-1/2 size-1 rounded-full bg-danger/70 shadow-[0_0_12px_rgba(251,113,133,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute bottom-10 left-1/4 size-1.5 rounded-full bg-secondary/70 shadow-[0_0_12px_rgba(56,189,248,0.8)]"
                aria-hidden="true"></div>
            <div class="absolute right-10 top-1/3 size-1 rounded-full bg-accent/70 shadow-[0_0_12px_rgba(163,230,53,0.8)]"
                aria-hidden="true"></div>
        </div>
        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-danger/20 bg-danger/5 px-3.5 py-2">
            <svg class="size-4 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3 20 6v5c0 5-3.4 8.2-8 10-4.6-1.8-8-5-8-10V6l8-3Z"></path>
                <path d="M12 8v4"></path>
                <path d="M12 15h.01"></path>
            </svg>
            <span class="text-xs font-semibold tracking-wide text-danger">Akses tidak diizinkan</span>
        </div>
        <h1 class="max-w-xl text-balance text-3xl font-extrabold tracking-tight text-foreground sm:text-4xl">
            Kamu tidak memiliki izin akses.
        </h1>
        <p class="mt-4 max-w-lg text-pretty text-sm leading-7 text-muted sm:text-base">
            Maaf, akunmu tidak memiliki izin yang diperlukan untuk membuka halaman ini. Jika menurutmu akses ini
            seharusnya tersedia, silakan hubungi administrator.
        </p>
        <div class="mt-8 flex w-full flex-col items-center justify-center gap-3 sm:w-auto sm:flex-row">
            <a href="{{ route('home') }}"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-background shadow-lg shadow-primary/15 transition duration-200 hover:-translate-y-0.5 hover:bg-cyan-bright hover:shadow-primary/25 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 10 9-7 9 7"></path>
                    <path d="M5 9v11h14V9"></path>
                    <path d="M9 20v-6h6v6"></path>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('contact.index') }}"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground transition duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary sm:w-auto">
                Hubungi Administrator
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
        <p class="mt-4 text-xs leading-6 text-subtle">
            Akses dibatasi untuk melindungi keamanan dan data pengguna.
        </p>
    </section>
</x-layouts.error>
