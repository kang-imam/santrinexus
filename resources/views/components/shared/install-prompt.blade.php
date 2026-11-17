<div id="install-prompt"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 px-5 py-8 backdrop-blur-sm"
    aria-hidden="true">
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-border bg-background p-6 shadow-2xl">
        <button type="button" id="install-prompt-close"
            class="absolute right-4 top-4 flex size-10 items-center justify-center rounded-full text-muted-foreground transition hover:bg-muted hover:text-foreground"
            aria-label="Tutup popup">
            <x-shared.icon name="heroicon-o-x-mark" fallback="fas-fa-xmark" class="size-5" />
        </button>
        <div class="mb-5 flex size-16 items-center justify-center rounded-2xl bg-primary/10 text-primary">
            <x-shared.icon name="heroicon-o-device-phone-mobile" fallback="fas-fa-mobile-screen-button"
                class="size-8" />
        </div>
        <h2 class="text-xl font-bold tracking-tight text-foreground">Install SantriNexus</h2>
        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">Nikmati pengalaman SantriNexus yang lebih praktis.
            Pasang aplikasi di perangkatmu agar lebih mudah diakses kapan saja.</p>
        <div id="install-prompt-help"
            class="mt-4 hidden rounded-xl border border-border bg-muted/50 p-4 text-sm leading-relaxed text-muted-foreground">
        </div>
        <div class="mt-6 flex flex-col gap-3">
            <button type="button" id="install-prompt-button"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 font-semibold text-background transition hover:opacity-90">
                <x-shared.icon name="heroicon-o-arrow-down-tray" fallback="fas-fa-download" class="size-5" />
                <span>Install Sekarang</span>
            </button>
            <button type="button" id="install-prompt-later"
                class="w-full rounded-xl px-5 py-3 text-sm font-medium text-muted-foreground transition hover:bg-muted hover:text-foreground">Nanti
                Saja</button>
        </div>
    </div>
</div>
