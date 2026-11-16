@auth
    @php
        $user = auth()->user();
        $accountNavigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('account');
        $menuId = 'header-account-menu-' . \Illuminate\Support\Str::uuid();
        $logoutIcon = config('navigation.settings.logout_icon', 'heroicon-o-arrow-left-start-on-rectangle');
        $isHeroicon = is_string($logoutIcon) && str_starts_with($logoutIcon, 'heroicon-');
        $isFontAwesome =
            is_string($logoutIcon) &&
            (str_starts_with($logoutIcon, 'fa-') ||
                str_starts_with($logoutIcon, 'fas ') ||
                str_starts_with($logoutIcon, 'far ') ||
                str_starts_with($logoutIcon, 'fab '));
    @endphp
    <div x-data="{
        open: false,
        close() {
            this.open = false;
        },
        toggle() {
            this.open = !this.open;
        },
        handleEscape() {
            if (this.open) {
                this.close();
                this.$refs.trigger.focus();
            }
        }
    }" @keydown.escape.window="handleEscape()" @click.outside="close()" class="relative">
        <button x-ref="trigger" type="button" @click="toggle()" :aria-expanded="open.toString()" aria-haspopup="true"
            aria-controls="{{ $menuId }}" aria-label="Menu akun {{ $user->name }}"
            class="inline-flex max-w-52 items-center gap-2 rounded-xl border border-border bg-surface px-2 py-1.5 text-left transition duration-200 hover:border-border-bright hover:bg-surface-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
            <x-navigations.header.partials.user-avatar :user="$user" size="md" />
            <span class="hidden min-w-0 flex-1 flex-col text-left sm:flex">
                <span class="truncate text-xs font-semibold text-foreground">{{ $user->name }}</span>
                <span class="truncate text-[10px] text-muted">Akun Saya</span>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="hidden size-4 shrink-0 text-muted transition-transform duration-200 sm:block"
                :class="{ 'rotate-180': open }" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <div id="{{ $menuId }}" x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute right-0 top-full z-50 w-64 max-w-[calc(100vw-2rem)] pt-3" :aria-hidden="(!open).toString()">
            <div class="overflow-hidden rounded-2xl border border-border bg-surface shadow-2xl shadow-black/20">
                <div class="border-b border-border px-4 py-3">
                    <p class="truncate text-sm font-semibold text-foreground">{{ $user->name }}</p>
                    <p class="truncate text-xs text-muted">{{ $user->email }}</p>
                </div>
                <div class="space-y-1 p-2">
                    @forelse ($accountNavigation as $item)
                        <x-navigations.header.partials.menu-item :item="$item" variant="dropdown" />
                    @empty
                        <p class="px-3 py-2 text-sm text-muted">Belum ada menu akun.</p>
                    @endforelse
                </div>
                <div class="border-t border-border p-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-danger transition duration-200 hover:bg-danger/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                            @if ($isHeroicon)
                                <x-dynamic-component :component="$logoutIcon" class="size-4 shrink-0" aria-hidden="true" />
                            @elseif ($isFontAwesome)
                                <span class="inline-flex size-4 shrink-0 items-center justify-center" aria-hidden="true">
                                    <i class="{{ $logoutIcon }}"></i>
                                </span>
                            @endif
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="flex shrink-0 items-center gap-2">
        <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold text-muted transition duration-200 hover:bg-surface-hover hover:text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
            Masuk
        </a>
        <a href="{{ route('register') }}"
            class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-background shadow-sm transition duration-200 hover:bg-cyan-bright focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
            Daftar
        </a>
    </div>
@endauth
