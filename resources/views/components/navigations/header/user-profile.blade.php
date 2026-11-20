@props([
    'accountNavigation' => [],
    'mode' => 'desktop',
])
@auth
    @php
        $user = auth()->user();
        $menuId = 'header-account-menu-' . \Illuminate\Support\Str::uuid();
        $mode = is_string($mode) && in_array($mode, ['desktop', 'mobile'], true) ? $mode : 'desktop';
        $accountNavigation = is_array($accountNavigation)
            ? array_values(
                array_filter($accountNavigation, static function ($item): bool {
                    if (!is_array($item) || !filter_var($item['visible'] ?? true, FILTER_VALIDATE_BOOLEAN)) {
                        return false;
                    }
                    $children = $item['children'] ?? [];
                    $hasChildren = is_array($children) && count($children) > 0;
                    $url = $item['url'] ?? null;
                    $isNavigable = filter_var($item['navigable'] ?? true, FILTER_VALIDATE_BOOLEAN);
                    return $hasChildren || ($isNavigable && is_string($url) && trim($url) !== '');
                }),
            )
            : [];
        $hasLogoutRoute = \Illuminate\Support\Facades\Route::has('logout');
        $accountName = is_string($user?->name) && trim($user->name) !== '' ? trim($user->name) : 'Pengguna';
        $accountEmail = is_string($user?->email) ? trim($user->email) : '';
    @endphp
    @if ($mode === 'mobile')
        <div x-data="{ open: false, close() { this.open = false; }, toggle() { this.open = !this.open; } }" @keydown.escape.window="close()" class="w-full">
            <button type="button" @click="toggle()" :aria-expanded="open.toString()" aria-controls="{{ $menuId }}"
                class="group flex min-h-16 w-full min-w-0 items-center gap-3 rounded-2xl border border-border/80 bg-surface/80 p-3 text-left shadow-sm shadow-black/10 transition-all duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                <x-navigations.header.partials.user-avatar :user="$user" size="lg" />
                <span class="flex min-w-0 flex-1 flex-col">
                    <span class="truncate text-sm font-bold text-foreground">{{ $accountName }}</span>
                    <span class="truncate text-xs text-muted">{{ $accountEmail }}</span>
                    <span class="mt-1 text-[11px] font-semibold text-primary">Menu akun</span>
                </span>
                <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                    class="size-4 shrink-0 text-muted transition-transform duration-200 group-hover:text-primary"
                    ::class="{ 'rotate-180 text-primary': open }" aria-hidden="true" />
            </button>
            <div id="{{ $menuId }}" x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2" :aria-hidden="(!open).toString()" :inert="!open"
                class="mt-2 overflow-hidden rounded-2xl border border-border/80 bg-surface/90 shadow-lg shadow-black/10">
                <div class="border-b border-border/70 bg-surface-raised/50 px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-muted">Akun aktif</p>
                    <p class="mt-1 truncate text-xs font-medium text-foreground">{{ $accountName }}</p>
                </div>
                <div class="p-2">
                    <x-navigations.header.partials.mobile-menu :items="$accountNavigation" />
                </div>
                @if ($hasLogoutRoute)
                    <div class="border-t border-border/80 bg-background/30 p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="group flex min-h-12 w-full items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-semibold text-danger transition-colors hover:bg-danger/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                <x-shared.icon :name="config(
                                    'navigation.settings.logout_icon',
                                    'heroicon-o-arrow-left-start-on-rectangle',
                                )" :fallback="config(
                                    'navigation.settings.logout_icon_fallback',
                                    'fa-solid fa-right-from-bracket',
                                )" class="size-4 shrink-0"
                                    aria-hidden="true" />
                                <span>Keluar dari akun</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div x-data="{
            open: false,
            getItems() { return Array.from(this.$refs.menu?.querySelectorAll('a[href]:not([aria-disabled=true]):not([tabindex=\'-1\']), button:not([disabled]):not([tabindex=\'-1\'])') ?? []).filter(item => item.getClientRects().length > 0 && !item.closest('[inert]')); },
            openMenu(position = 'first') {
                this.open = true;
                this.$nextTick(() => {
                    const items = this.getItems();
                    if (!items.length) return;
                    (position === 'last' ? items[items.length - 1] : items[0]).focus();
                });
            },
            close(restoreFocus = false) {
                if (!this.open) return;
                this.open = false;
                if (restoreFocus) this.$nextTick(() => this.$refs.trigger?.focus());
            },
            toggle() { this.open ? this.close() : this.openMenu(); },
            moveFocus(direction) {
                const items = this.getItems();
                if (!items.length) return;
                const current = items.indexOf(document.activeElement);
                const next = current < 0 ? (direction > 0 ? 0 : items.length - 1) : (current + direction + items.length) % items.length;
                items[next].focus();
            },
            handleKeydown(event) {
                if (event.key === 'Escape' && this.open) {
                    event.preventDefault();
                    event.stopPropagation();
                    this.close(true);
                    return;
                }
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    this.open ? this.moveFocus(1) : this.openMenu('first');
                    return;
                }
                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.open ? this.moveFocus(-1) : this.openMenu('last');
                    return;
                }
                if (event.key === 'Home' && this.open) {
                    event.preventDefault();
                    this.openMenu('first');
                    return;
                }
                if (event.key === 'End' && this.open) {
                    event.preventDefault();
                    this.openMenu('last');
                }
            }
        }" @keydown="handleKeydown($event)" @click.outside="close()"
            class="relative w-full lg:w-auto">
            <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()"
                aria-controls="{{ $menuId }}" aria-label="Menu akun {{ $accountName }}"
                class="group flex w-full min-w-0 items-center gap-2 rounded-xl border border-transparent bg-transparent px-2 py-1.5 text-left transition-all duration-200 hover:border-border/80 hover:bg-surface/80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary lg:max-w-60">
                <x-navigations.header.partials.user-avatar :user="$user" size="md" />
                <span class="flex min-w-0 flex-1 flex-col">
                    <span
                        class="truncate text-xs font-bold text-foreground transition-colors group-hover:text-primary">{{ $accountName }}</span>
                    <span class="truncate text-[10px] font-medium text-muted">Akun Saya</span>
                </span>
                <x-shared.icon name="heroicon-o-chevron-down" fallback="fa-solid fa-chevron-down"
                    class="size-4 shrink-0 text-muted transition-transform duration-200 group-hover:text-primary"
                    ::class="{ 'rotate-180 text-primary': open }" aria-hidden="true" />
            </button>
            <div id="{{ $menuId }}" x-ref="menu" x-cloak x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-2 scale-95" :aria-hidden="(!open).toString()"
                :inert="!open"
                class="absolute right-0 top-full z-50 w-72 max-w-[calc(100vw-2rem)] origin-top-right pt-3">
                <div
                    class="overflow-hidden rounded-2xl border border-border/80 bg-surface/95 shadow-2xl shadow-black/20 backdrop-blur-xl">
                    <div class="border-b border-border/70 bg-surface-raised/60 px-4 py-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-navigations.header.partials.user-avatar :user="$user" size="lg" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-foreground">{{ $accountName }}</p>
                                <p class="truncate text-xs text-muted">{{ $accountEmail }}</p>
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Menu akun" class="max-h-80 overflow-y-auto overscroll-contain p-2 scrollbar-thin">
                        @forelse ($accountNavigation as $item)
                            @if (is_array($item['children'] ?? null) && count($item['children']) > 0)
                                <x-navigations.header.partials.dropdown-menu :item="$item" mode="inline" />
                            @else
                                <x-navigations.header.partials.menu-item :item="$item" variant="dropdown"
                                    class="w-full" />
                            @endif
                        @empty
                            @if (config('navigation.settings.show_empty_state', false))
                                <p class="px-3 py-4 text-center text-xs font-medium text-muted" role="status">Menu akun
                                    belum tersedia.</p>
                            @endif
                        @endforelse
                    </nav>
                    @if ($hasLogoutRoute)
                        <div class="border-t border-border/80 bg-background/30 p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="group flex min-h-11 w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-danger transition-colors hover:bg-danger/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                    <x-shared.icon :name="config(
                                        'navigation.settings.logout_icon',
                                        'heroicon-o-arrow-left-start-on-rectangle',
                                    )" :fallback="config(
                                        'navigation.settings.logout_icon_fallback',
                                        'fa-solid fa-right-from-bracket',
                                    )" class="size-4 shrink-0"
                                        aria-hidden="true" />
                                    <span>Keluar dari akun</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@else
    @php
        $loginRoute = \Illuminate\Support\Facades\Route::has('login') ? route('login') : null;
        $registerRoute = \Illuminate\Support\Facades\Route::has('register') ? route('register') : null;
    @endphp
    @if ($loginRoute || $registerRoute)
        <div class="flex w-full min-w-0 items-center gap-2 lg:w-auto">
            @if ($loginRoute)
                <a href="{{ $loginRoute }}"
                    class="group inline-flex min-h-11 min-w-0 flex-1 items-center justify-center gap-2 rounded-xl border border-border-bright/70 bg-surface/70 px-4 py-2.5 text-sm font-semibold text-foreground transition-all duration-200 hover:border-primary/50 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary lg:flex-none">
                    <x-shared.icon name="heroicon-o-arrow-right-end-on-rectangle" fallback="fa-solid fa-right-to-bracket"
                        class="size-4 shrink-0 transition-transform duration-200 group-hover:-translate-x-0.5"
                        aria-hidden="true" />
                    <span>Masuk</span>
                </a>
            @endif
            @if ($registerRoute)
                <a href="{{ $registerRoute }}"
                    class="group inline-flex min-h-11 min-w-0 flex-1 items-center justify-center gap-2 rounded-xl border border-primary/70 bg-primary px-4 py-2.5 text-sm font-bold text-background shadow-lg shadow-primary/10 transition-all duration-200 hover:border-cyan-bright hover:bg-cyan-bright hover:shadow-primary/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary lg:flex-none">
                    <x-shared.icon name="heroicon-o-user-plus" fallback="fa-solid fa-user-plus"
                        class="size-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                        aria-hidden="true" />
                    <span>Daftar</span>
                </a>
            @endif
        </div>
    @endif
@endauth
