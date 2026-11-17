@php
    $navigationBuilder = app(\App\Support\Navigation\NavigationBuilder::class);
    $headerNavigation = $navigationBuilder->build('header');
    $accountNavigation = $navigationBuilder->build('account');
@endphp
<header x-data="{
    mobileMenuOpen: false,
    previousOverflow: '',
    toggleMobileMenu() {
        this.mobileMenuOpen ? this.closeMobileMenu(true) : this.openMobileMenu();
    },
    openMobileMenu() {
        if (this.mobileMenuOpen) return;
        this.previousOverflow = document.documentElement.style.overflow;
        document.documentElement.style.overflow = 'hidden';
        this.mobileMenuOpen = true;
        this.$nextTick(() => {
            this.$refs.mobilePanel?.querySelector('a[href]:not([aria-disabled=true]), button:not([disabled])')?.focus();
        });
    },
    closeMobileMenu(restoreFocus = false) {
        if (!this.mobileMenuOpen) return;
        this.mobileMenuOpen = false;
        document.documentElement.style.overflow = this.previousOverflow;
        if (restoreFocus) {
            this.$nextTick(() => this.$refs.mobileToggle?.focus());
        }
    },
    handleEscape() {
        if (this.mobileMenuOpen) this.closeMobileMenu(true);
    },
    handleResize() {
        if (window.matchMedia('(min-width: 1024px)').matches) this.closeMobileMenu();
    },
    destroy() {
        document.documentElement.style.overflow = this.previousOverflow;
    }
}" @keydown.escape.window="handleEscape()" @resize.window.debounce.150ms="handleResize()"
    class="sticky top-0 z-50 w-full border-b border-border/70 bg-background/80 shadow-sm shadow-black/10 backdrop-blur-2xl supports-[backdrop-filter]:bg-background/75">
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"
        aria-hidden="true"></div>
    <div
        class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-3 px-4 sm:gap-5 sm:px-6 lg:h-[4.5rem] lg:px-8">
        <div class="flex min-w-0 shrink-0 items-center">
            <x-navigations.header.brand />
        </div>
        <div class="hidden min-w-0 flex-1 items-center justify-center lg:flex">
            <x-navigations.header.menu :items="$headerNavigation" />
        </div>
        <div class="hidden shrink-0 items-center justify-end lg:flex">
            <x-navigations.header.user-profile :account-navigation="$accountNavigation" />
        </div>
        <div class="ml-auto flex shrink-0 items-center gap-2 lg:hidden">
            <button x-ref="mobileToggle" type="button" @click="toggleMobileMenu()"
                :aria-expanded="mobileMenuOpen.toString()" aria-controls="header-mobile-menu"
                :aria-label="mobileMenuOpen ? 'Tutup navigasi' : 'Buka navigasi'"
                class="group relative inline-flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-border/80 bg-surface/80 text-foreground shadow-sm shadow-black/10 transition-all duration-200 hover:border-primary/50 hover:bg-primary/10 hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-95">
                <x-shared.icon x-cloak name="heroicon-o-bars-3" fallback="fa-solid fa-bars"
                    class="absolute size-5 transition-all duration-150" x-show="!mobileMenuOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 rotate-90 scale-75"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100" aria-hidden="true" />
                <x-shared.icon x-cloak name="heroicon-o-x-mark" fallback="fa-solid fa-xmark"
                    class="absolute size-5 transition-all duration-150" x-show="mobileMenuOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -rotate-90 scale-75"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100" aria-hidden="true" />
            </button>
        </div>
    </div>
    <div id="header-mobile-menu" x-ref="mobilePanel" x-cloak x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="closeMobileMenu()" @click="if ($event.target.closest('a[href]')) closeMobileMenu()"
        :aria-hidden="(!mobileMenuOpen).toString()" :inert="!mobileMenuOpen"
        class="absolute inset-x-0 top-full z-50 max-h-[calc(100dvh-4rem)] overflow-y-auto overscroll-contain border-b border-border/80 bg-background/95 shadow-2xl shadow-black/30 backdrop-blur-2xl lg:hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent"
            aria-hidden="true"></div>
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6">
            <x-navigations.header.partials.mobile-menu :items="$headerNavigation" />
            <div class="mt-5 border-t border-border/80 pt-5">
                <x-navigations.header.user-profile :account-navigation="$accountNavigation" />
            </div>
            <div class="mt-5 border-t border-border/50 pt-4 text-center">
                <p class="text-[10px] font-medium tracking-wider text-subtle">SANTRINEXUS</p>
            </div>
        </div>
    </div>
</header>
