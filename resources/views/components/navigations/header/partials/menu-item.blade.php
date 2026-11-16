@props([
    'href' => '#',
    'label',
    'active' => false,
])
<a href="{{ $href }}" @class([
    'transition-colors',
    'text-primary hover:text-cyan-bright' => $active,
    'text-muted hover:text-primary' => !$active,
])>
    {{ $label }}
</a>