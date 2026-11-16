@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->get('header');
    $showIcons = config('navigation.settings.show_icons', true);
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
        @if (count($children))
            <div x-data="{ open: @js($isActive) }" class="overflow-hidden rounded-xl border border-border bg-surface">
                <button x-ref="trigger" type="button" @click="open = !open"
                    @keydown.escape.stop="open = false; $refs.trigger.focus()" :aria-expanded="open.toString()"
                    aria-controls="{{ $submenuId }}"
                    class="flex min-h-12 w-full items-center gap-3 px-3 py-3 text-left text-sm font-semibold transition duration-200 focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-primary {{ $isActive ? 'text-primary' : 'text-muted hover:bg-surface-hover hover:text-foreground' }}">
                    @if ($showIcons && $isHeroicon)
                        <x-dynamic-component :component="$icon" class="size-5 shrink-0" aria-hidden="true" />
                    @elseif ($showIcons && $isFontAwesome)
                        <span class="inline-flex size-5 shrink-0 items-center justify-center" aria-hidden="true">
                            <i class="{{ $icon }}"></i>
                        </span>
                    @endif
                    <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="size-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        aria-hidden="true">
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
                    :inert="!open" class="border-t border-border bg-background/50 px-2 py-2">
                    <div class="space-y-1">
                        @foreach ($children as $child)
                            <x-navigations.header.partials.menu-item :item="$child" variant="mobile"
                                class="w-full" />
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif (!empty($item['url']))
            <x-navigations.header.partials.menu-item :item="$item" variant="mobile"
                class="border border-border bg-surface" />
        @endif
    @empty
        <p class="rounded-xl border border-border bg-surface px-4 py-3 text-sm text-muted">
            Navigasi belum tersedia.
        </p>
    @endforelse
</nav>
