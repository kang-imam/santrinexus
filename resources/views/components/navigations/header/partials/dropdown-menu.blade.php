@props(['item'])
@php
    $children = array_values(
        array_filter(
            $item['children'] ?? [],
            fn($child) => is_array($child) && is_string($child['url'] ?? null) && $child['url'] !== '',
        ),
    );
    $isActive = (bool) ($item['active'] ?? false);
    $showIcons = config('navigation.settings.show_icons', true);
    $icon = is_string($item['icon'] ?? null) ? trim($item['icon']) : '';
    $isHeroicon = $icon !== '' && str_starts_with($icon, 'heroicon-');
    $isFontAwesome =
        $icon !== '' &&
        (preg_match('/^(?:(?:fa[srb])|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) === 1 ||
            preg_match('/^fa-[a-z0-9-]+$/i', $icon) === 1);
    if (
        $isFontAwesome &&
        preg_match('/^(?:(?:fa[srb])|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) !== 1
    ) {
        $icon = 'fa-solid ' . $icon;
    }
    $menuId = 'header-dropdown-' . \Illuminate\Support\Str::uuid();
@endphp
@if ($children !== [])
    <div x-data="{ open: false, close() { this.open = false }, focusItem(position) { this.open = true;
            this.$nextTick(() => this.$refs[position]?.focus()) } }"
        @mouseenter="if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) open = true"
        @mouseleave="if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) close()"
        @keydown.escape.window="if (open) { close(); $refs.trigger.focus() }" @click.outside="close()"
        @focusout="if (!$el.contains($event.relatedTarget)) close()"
        @keydown.arrow-down.prevent="if (open) focusItem('firstItem')"
        @keydown.arrow-up.prevent="if (open) focusItem('lastItem')"
        @keydown.home.prevent="if (open) focusItem('firstItem')" @keydown.end.prevent="if (open) focusItem('lastItem')"
        class="relative">
        <button x-ref="trigger" type="button" @click="open = !open"
            @keydown.arrow-down.prevent="focusItem('firstItem')" @keydown.arrow-up.prevent="focusItem('lastItem')"
            :aria-expanded="open.toString()" aria-haspopup="true" aria-controls="{{ $menuId }}"
            class="group inline-flex min-h-10 items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary {{ $isActive ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5' : 'text-muted hover:bg-surface-hover hover:text-foreground active:scale-[0.98]' }}">
            @if ($showIcons && $isHeroicon)
                <x-dynamic-component :component="$icon"
                    class="size-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                    aria-hidden="true" />
            @elseif ($showIcons && $isFontAwesome)
                <span
                    class="inline-flex size-4 shrink-0 items-center justify-center transition-transform duration-200 group-hover:scale-110"
                    aria-hidden="true">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif
            <span>{{ $item['label'] }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 shrink-0 text-subtle transition-all duration-200 group-hover:text-primary"
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
            x-transition:leave-end="opacity-0 -translate-y-2 scale-[0.98]"
            @click="if ($event.target.closest('a')) close()"
            class="absolute left-0 top-full z-50 w-64 origin-top-left pt-3" :aria-hidden="(!open).toString()"
            :inert="!open">
            <div
                class="overflow-hidden rounded-2xl border border-border/80 bg-surface/95 p-2 shadow-2xl shadow-black/30 ring-1 ring-white/5 backdrop-blur-2xl">
                <div class="space-y-1">
                    @foreach ($children as $index => $child)
                        @if ($index === 0)
                            <x-navigations.header.partials.menu-item :item="$child" variant="dropdown"
                                x-ref="firstItem" />
                        @elseif ($index === count($children) - 1)
                            <x-navigations.header.partials.menu-item :item="$child" variant="dropdown"
                                x-ref="lastItem" />
                        @else
                            <x-navigations.header.partials.menu-item :item="$child" variant="dropdown" />
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
