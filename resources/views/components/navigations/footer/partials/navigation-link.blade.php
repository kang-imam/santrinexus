@props([
    'href' => '#',
    'label',
    'icon' => 'heroicon-o-chevron-right',
])
<a href="{{ $href }}"
    class="group inline-flex items-center gap-2.5 text-sm text-muted transition-all duration-200 hover:translate-x-1 hover:text-primary">
    <x-dynamic-component :component="$icon"
        class="h-4 w-4 shrink-0 text-subtle transition-colors group-hover:text-primary" />
    <span>{{ $label }}</span>
</a>
