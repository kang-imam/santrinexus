@auth
    @php
        $user = auth()->user();
        $accountNavigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('account');
        $menuId = 'header-account-menu-' . \Illuminate\Support\Str::uuid();
        $logoutIcon = is_string(config('navigation.settings.logout_icon'))
            ? trim(config('navigation.settings.logout_icon'))
            : 'heroicon-o-arrow-left-start-on-rectangle';
        $isHeroicon = $logoutIcon !== '' && str_starts_with($logoutIcon, 'heroicon-');
        $isFontAwesome =
            $logoutIcon !== '' &&
            (preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $logoutIcon) === 1 ||
                preg_match('/^fa-[a-z0-9-]+$/i', $logoutIcon) === 1);
        if (
            $isFontAwesome &&
            preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $logoutIcon) !== 1
        ) {
            $logoutIcon = 'fa-solid ' . $logoutIcon;
        }
    @endphp
    <div x-data="{ open: false, close() { this.open = false; }, toggle() { this.open = !this.open; }, handleEscape() { if (this.open) { this.close();
                this.$refs.trigger.focus(); } } }" @keydown.escape.window="handleEscape()" @click.outside="close()"
        class="relative w-full lg:w-auto">
        <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()" aria-haspopup="true"
            aria-controls="{{ $menuId }}" aria-label="Menu akun {{ $user->name }}"
            class="group flex w-full items-center gap-3 rounded-2xl border border-border/80 bg-surface/80 p-3 text-left shadow-sm shadow-black/10 transition-all duration-200 hover:border-primary/40 hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.99] lg:w-auto lg:max-w-60 lg:gap-2 lg:rounded-xl lg:px-2 lg:py-1.5">
            <x-navigations.header.partials.user-avatar :user="$user" size="lg" />
            <span class="flex min-w-0 flex-1 flex-col text-left lg:hidden">
                <span class="truncate text-sm font-bold text-foreground">{{ $user->name }}</span>
                <span class="truncate text-xs text-muted">{{ $user->email }}</span>
                <span class="mt-1 text-[11px] font-semibold text-primary">Lihat menu akun</span>
            </span>
            <span class="hidden min-w-0 flex-1 flex-col text-left lg:flex">
                <span
                    class="truncate text-xs font-bold text-foreground transition-colors duration-200 group-hover:text-primary">{{ $user->name }}</span>
                <span class="truncate text-[10px] font-medium text-muted">Akun Saya</span>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 shrink-0 text-muted transition-all duration-200 group-hover:text-primary"
                :class="{ 'rotate-180 text-primary': open }" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <div id="{{ $menuId }}" x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
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
                            <p class="truncate text-sm font-bold text-foreground">{{ $user->name }}</p>
                            <p class="mt-0.5 truncate text-xs text-muted">{{ $user->email }}</p>
                            <span class="mt-2 inline-flex items-center gap-1.5 text-[10px] font-semibold text-primary">
                                <span class="size-1.5 rounded-full bg-accent"></span>
                                Akun aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="max-h-72 overflow-y-auto p-2 scrollbar-thin">
                    @forelse ($accountNavigation as $item)
                        <x-navigations.header.partials.menu-item :item="$item" variant="dropdown" />
                    @empty
                        @if (config('navigation.settings.show_empty_state', false))
                            <p class="px-3 py-3 text-center text-xs font-medium text-muted">Belum ada menu akun.</p>
                        @endif
                    @endforelse
                </div>
                <div class="border-t border-border/80 bg-background/30 p-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex min-h-11 w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-danger transition-all duration-200 hover:bg-danger/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.99]">
                            @if ($isHeroicon)
                                <x-dynamic-component :component="$logoutIcon"
                                    class="size-4 shrink-0 transition-transform duration-200 group-hover:translate-x-0.5"
                                    aria-hidden="true" />
                            @elseif ($isFontAwesome)
                                <span class="inline-flex size-4 shrink-0 items-center justify-center" aria-hidden="true">
                                    <i class="{{ $logoutIcon }}"></i>
                                </span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                    class="size-4 shrink-0" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <path d="M16 17l5-5-5-5M21 12H9" />
                                </svg>
                            @endif
                            <span>Keluar dari akun</span>
                            <span
                                class="ml-auto text-[10px] font-medium text-danger/60 transition-transform duration-200 group-hover:translate-x-0.5">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="grid w-full grid-cols-2 gap-2.5 lg:flex lg:w-auto lg:shrink-0 lg:gap-2">
        <a href="{{ route('login') }}"
            class="inline-flex min-h-11 items-center justify-center rounded-xl border border-border/80 bg-surface/80 px-4 py-2.5 text-sm font-semibold text-foreground shadow-sm transition-all duration-200 hover:border-primary/40 hover:bg-surface-hover hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.98] lg:min-h-10 lg:bg-transparent lg:px-4">
            Masuk
        </a>
        <a href="{{ route('register') }}"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-primary/20 bg-primary px-4 py-2.5 text-sm font-bold text-background shadow-md shadow-primary/10 transition-all duration-200 hover:border-cyan-bright hover:bg-cyan-bright hover:shadow-lg hover:shadow-primary/20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:scale-[0.98] lg:min-h-10">
            Daftar
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M3.5 10a.75.75 0 0 1 .75-.75h9.69L10.22 5.53a.75.75 0 0 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H4.25A.75.75 0 0 1 3.5 10Z"
                    clip-rule="evenodd" />
            </svg>
        </a>
    </div>
@endauth
