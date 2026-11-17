@props(['accountNavigation' => []])
@auth
    @php
        $user = auth()->user();
        $menuId = 'header-account-menu-' . \Illuminate\Support\Str::uuid();
        $accountNavigation = is_array($accountNavigation)
            ? array_values(
                array_filter($accountNavigation, static function ($item) {
                    return is_array($item) &&
                        ($item['visible'] ?? true) === true &&
                        ($item['navigable'] ?? true) === true &&
                        is_string($item['url'] ?? null) &&
                        trim($item['url']) !== '' &&
                        is_string($item['label'] ?? null) &&
                        trim($item['label']) !== '';
                }),
            )
            : [];
        $hasLogoutRoute = \Illuminate\Support\Facades\Route::has('logout');
    @endphp
    <div x-data="{
        open: false,
        items() {
            return [...this.$refs.menu.querySelectorAll('a[href]:not([aria-disabled=true])')];
        },
        openMenu(position = 'first') {
            this.open = true;
            this.$nextTick(() => {
                const items = this.items();
                if (!items.length) return;
                (position === 'last' ? items[items.length - 1] : items[0])
                .focus();
            });
        },
        close(restoreFocus = false) {
            this.open = false;
            if (restoreFocus) this.$nextTick(() => this.$refs.trigger?.focus());
        },
        toggle() {
            this.open ? this.close() : this.openMenu();
        },
        moveFocus(direction) {
            const items = this.items();
            if (!items.length) return;
            const index = items.indexOf(document.activeElement);
            const next = direction === 'next' ?
                (index + 1) % items.length :
                (index - 1 + items.length) % items.length;
            items[next].focus();
        },
        handleKeydown(event) {
            if (event.key === 'Escape' && this.open) {
                event.preventDefault();
                this.close(true);
            }
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.open ? this.moveFocus('next') : this.openMenu('first');
            }
            if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.open ? this.moveFocus('previous') : this.openMenu('last');
            }
            if (event.key === 'Home' && this.open) {
                event.preventDefault();
                this.openMenu('first');
            }
            if (event.key === 'End' && this.open) {
                event.preventDefault();
                this.openMenu('last');
            }
        }
    }" @keydown="handleKeydown($event)" @click.outside="close()" class="relative w-full lg:w-auto">
        <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()"
            aria-controls="{{ $menuId }}" aria-label="Menu akun {{ $user->name ?: 'Pengguna' }}"
            class="group flex w-full items-center gap-3 rounded-2xl border border-border/80 bg-surface/80 p-3 text-left shadow-sm shadow-black/10 transition-all duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.99] lg:w-auto lg:max-w-60 lg:gap-2 lg:rounded-xl lg:px-2 lg:py-1.5">
            <x-navigations.header.partials.user-avatar :user="$user" size="lg" />
            <span class="flex min-w-0 flex-1 flex-col text-left lg:hidden">
                <span class="truncate text-sm font-bold text-foreground">{{ $user->name ?: 'Pengguna' }}</span>
                <span class="truncate text-xs text-muted">{{ $user->email }}</span>
                <span class="mt-1 text-[11px] font-semibold text-primary">Lihat menu akun</span>
            </span>
            <span class="hidden min-w-0 flex-1 flex-col text-left lg:flex">
                <span
                    class="truncate text-xs font-bold text-foreground transition-colors duration-200 group-hover:text-primary">{{ $user->name ?: 'Pengguna' }}</span>
                <span class="truncate text-[10px] font-medium text-muted">Akun Saya</span>
            </span>
            <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                class="size-4 shrink-0 text-muted transition-all duration-200 group-hover:text-primary" ::class="{ 'rotate-180 text-primary': open }"
                aria-hidden="true" />
        </button>
        <div id="{{ $menuId }}" x-ref="menu" x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-[0.98]" @click="if ($event.target.closest('a')) close()"
            class="absolute right-0 top-full z-50 w-full min-w-64 origin-top-right pt-3 lg:w-72"
            :aria-hidden="(!open).toString()" :inert="!open">
            <div
                class="overflow-hidden rounded-2xl border border-border/80 bg-surface/95 shadow-2xl shadow-black/30 ring-1 ring-white/5 backdrop-blur-2xl">
                <div class="border-b border-border/80 bg-surface-raised/60 p-4">
                    <div class="flex items-center gap-3">
                        <x-navigations.header.partials.user-avatar :user="$user" size="lg" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-foreground">{{ $user->name ?: 'Pengguna' }}</p>
                            <p class="mt-0.5 truncate text-xs text-muted">{{ $user->email }}</p>
                            <span class="mt-2 inline-flex items-center gap-1.5 text-[10px] font-semibold text-primary">
                                <span class="size-1.5 rounded-full bg-accent" aria-hidden="true"></span>
                                Akun aktif
                            </span>
                        </div>
                    </div>
                </div>
                <nav aria-label="Menu akun" class="max-h-72 overflow-y-auto p-2 scrollbar-thin">
                    @forelse ($accountNavigation as $item)
                        <x-navigations.header.partials.menu-item :item="$item" variant="dropdown" />
                    @empty
                        @if (config('navigation.settings.show_empty_state', false))
                            <p class="px-3 py-3 text-center text-xs font-medium text-muted" role="status">Belum ada menu
                                akun.</p>
                        @endif
                    @endforelse
                </nav>
                @if ($hasLogoutRoute)
                    <div class="border-t border-border/80 bg-background/30 p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="group flex min-h-11 w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-danger transition-all duration-200 hover:bg-danger/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.99]">
                                <x-shared.icon :name="config(
                                    'navigation.settings.logout_icon',
                                    'heroicon-o-arrow-left-start-on-rectangle',
                                )" :fallback="config(
                                    'navigation.settings.logout_icon_fallback',
                                    'fa-solid fa-right-from-bracket',
                                )"
                                    class="size-4 shrink-0 transition-transform duration-200 group-hover:translate-x-0.5"
                                    aria-hidden="true" />
                                <span>Keluar dari akun</span>
                                <span
                                    class="ml-auto text-[10px] font-medium text-danger/60 transition-transform duration-200 group-hover:translate-x-0.5">Logout</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="grid w-full grid-cols-2 gap-2.5 lg:flex lg:w-auto lg:shrink-0 lg:gap-2">
        @if (\Illuminate\Support\Facades\Route::has('login'))
            <a href="{{ route('login') }}"
                class="inline-flex min-h-11 items-center justify-center rounded-xl border border-border/80 bg-surface/80 px-4 py-2.5 text-sm font-semibold text-foreground shadow-sm transition-all duration-200 hover:border-primary/40 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.98] lg:min-h-10 lg:bg-transparent lg:px-4">
                Masuk
            </a>
        @endif
        @if (\Illuminate\Support\Facades\Route::has('register'))
            <a href="{{ route('register') }}"
                class="group inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-primary/20 bg-primary px-4 py-2.5 text-sm font-bold text-background shadow-md shadow-primary/10 transition-all duration-200 hover:border-cyan-bright hover:bg-cyan-bright hover:shadow-lg hover:shadow-primary/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.98] lg:min-h-10">
                Daftar
                <x-shared.icon name="heroicon-o-arrow-right" fallback="fa-solid fa-arrow-right"
                    class="size-4 transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true" />
            </a>
        @endif
    </div>
@endauth
