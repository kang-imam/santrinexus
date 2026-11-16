@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('header');
    $showIcons = config('navigation.settings.show_icons', true);
    $showActiveState = config('navigation.settings.show_active_state', true);
@endphp
<nav class="space-y-2" aria-label="Navigasi utama mobile">
    @forelse ($navigation as $item)
        @php
            $children = array_values(
                array_filter(
                    $item['children'] ?? [],
                    fn($child) => is_array($child) && is_string($child['url'] ?? null) && $child['url'] !== '',
                ),
            );
            $isActive = (bool) ($item['active'] ?? false);
            $icon = is_string($item['icon'] ?? null) ? trim($item['icon']) : '';
            $isHeroicon = $icon !== '' && str_starts_with($icon, 'heroicon-');
            $isFontAwesome =
                $icon !== '' &&
                (preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) === 1 ||
                    preg_match('/^fa-[a-z0-9-]+$/i', $icon) === 1);
            if (
                $isFontAwesome &&
                preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) !== 1
            ) {
                $icon = 'fa-solid ' . $icon;
            }
            $submenuId = 'mobile-submenu-' . \Illuminate\Support\Str::uuid();
        @endphp
        @if ($children !== [])
            <div x-data="{ open: @js($isActive) }"
                class="overflow-hidden rounded-2xl border {{ $isActive && $showActiveState ? 'border-primary/25 bg-primary/4 shadow-sm shadow-primary/5' : 'border-border/80 bg-surface/70' }} transition-colors duration-200">
                <button x-ref="trigger" type="button" @click="open = !open"
                    @keydown.escape.stop="open = false; $refs.trigger.focus()" :aria-expanded="open.toString()"
                    aria-controls="{{ $submenuId }}"
                    class="group flex min-h-14 w-full items-center gap-3 px-3.5 py-3.5 text-left text-sm font-semibold transition-colors duration-200 hover:bg-surface-hover focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary {{ $isActive && $showActiveState ? 'text-primary' : 'text-foreground' }}">
                    @if ($showIcons && $isHeroicon)
                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-xl {{ $isActive && $showActiveState ? 'bg-primary/10 text-primary' : 'bg-surface-raised text-muted group-hover:text-primary' }} transition-colors duration-200">
                            <x-dynamic-component :component="$icon" class="size-5" aria-hidden="true" />
                        </span>
                    @elseif ($showIcons && $isFontAwesome)
                        <span
                            class="flex size-9 shrink-0 items-center justify-center rounded-xl {{ $isActive && $showActiveState ? 'bg-primary/10 text-primary' : 'bg-surface-raised text-muted group-hover:text-primary' }} transition-colors duration-200"
                            aria-hidden="true">
                            <i class="{{ $icon }}"></i>
                        </span>
                    @endif
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    @if ($isActive && $showActiveState)
                        <span class="size-1.5 shrink-0 rounded-full bg-primary" aria-hidden="true"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="size-4 shrink-0 text-subtle transition-all duration-200 group-hover:text-primary"
                        :class="{ 'rotate-180 text-primary': open }" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div id="{{ $submenuId }}" x-cloak x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" :aria-hidden="(!open).toString()"
                    :inert="!open" class="border-t border-border/70 bg-background/40 px-2 py-2">
                    <div class="space-y-1">
                        @foreach ($children as $child)
                            <x-navigations.header.partials.menu-item :item="$child" variant="mobile"
                                class="w-full" />
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif (!empty($item['url']))
            <div
                class="overflow-hidden rounded-2xl border {{ $isActive && $showActiveState ? 'border-primary/25 bg-primary/4 shadow-sm shadow-primary/5' : 'border-border/80 bg-surface/70' }} transition-colors duration-200">
                <x-navigations.header.partials.menu-item :item="$item" variant="mobile"
                    class="min-h-14 rounded-2xl px-3.5 py-3.5" />
            </div>
        @endif
    @empty
        @if (config('navigation.settings.show_empty_state', false))
            <div
                class="flex flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-border bg-surface/50 px-4 py-8 text-center">
                <span class="flex size-10 items-center justify-center rounded-xl bg-surface-raised text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="size-5"
                        aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </span>
                <p class="text-sm font-semibold text-foreground">Navigasi belum tersedia</p>
                <p class="text-xs text-muted">Silakan periksa kembali konfigurasi navigasi.</p>
            </div>
        @endif
    @endforelse
</nav>
