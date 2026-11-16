<header x-data="{
    mobileMenuOpen: false,
    closeMobileMenu() {
        this.mobileMenuOpen = false;
    },
    handleResize() {
        if (window.innerWidth >= 1024) {
            this.closeMobileMenu();
        }
    }
}" @keydown.escape.window="closeMobileMenu()" @resize.window="handleResize()"
    class="sticky top-0 z-50 w-full border-b border-border bg-background/90 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 shrink-0 items-center">
            <x-navigations.header.brand />
        </div>
        <nav class="hidden min-w-0 flex-1 items-center justify-center lg:flex" aria-label="Navigasi utama">
            <x-navigations.header.menu />
        </nav>
        <div class="hidden shrink-0 items-center justify-end lg:flex">
            <x-navigations.header.user-profile />
        </div>
        <div class="ml-auto flex shrink-0 items-center gap-2 lg:hidden">
            <x-navigations.header.user-profile />
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()"
                aria-controls="header-mobile-menu" :aria-label="mobileMenuOpen ? 'Tutup navigasi' : 'Buka navigasi'"
                class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl border border-border bg-surface text-foreground transition duration-200 hover:border-border-bright hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                <svg x-cloak x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-cloak x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="m18 6-12 12M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <div id="header-mobile-menu" x-cloak x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2" @click.outside="closeMobileMenu()"
        class="absolute inset-x-0 top-full z-50 max-h-[calc(100dvh-4rem)] overflow-y-auto border-b border-border bg-background shadow-2xl shadow-black/20 lg:hidden"
        :aria-hidden="(!mobileMenuOpen).toString()">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div @click="if ($event.target.closest('a')) closeMobileMenu()">
                <x-navigations.header.partials.mobile-menu />
            </div>
        </div>
    </div>
</header>
