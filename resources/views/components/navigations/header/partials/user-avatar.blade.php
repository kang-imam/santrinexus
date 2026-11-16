@props([
    'user' => null,
    'size' => 'md',
    'shape' => 'circle',
    'showStatus' => false,
    'status' => null,
    'alt' => null,
])
@php
    $user = $user ?? auth()->user();
    $name = trim((string) ($user?->name ?? 'Pengguna'));
    $name = $name !== '' ? $name : 'Pengguna';
    $words = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = collect($words)->take(2)->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
    $avatar = $user?->avatar_url;
    $avatar =
        is_string($avatar) &&
        filter_var($avatar, FILTER_VALIDATE_URL) &&
        in_array(parse_url($avatar, PHP_URL_SCHEME), ['http', 'https'], true)
            ? $avatar
            : null;
    $sizes = [
        'xs' => 'size-7 text-[10px]',
        'sm' => 'size-8 text-xs',
        'md' => 'size-9 text-sm',
        'lg' => 'size-11 text-base',
        'xl' => 'size-14 text-lg',
        '2xl' => 'size-20 text-2xl',
    ];
    $shapes = [
        'circle' => 'rounded-full',
        'rounded' => 'rounded-xl',
        'square' => 'rounded-none',
    ];
    $avatarSize = $sizes[$size] ?? $sizes['md'];
    $avatarShape = $shapes[$shape] ?? $shapes['circle'];
    $statusColors = [
        'online' => 'bg-accent',
        'offline' => 'bg-subtle',
        'busy' => 'bg-danger',
        'away' => 'bg-warning',
    ];
    $statusColor = $statusColors[$status] ?? null;
    $imageAlt = $alt ?? 'Foto profil ' . $name;
    $avatarId = 'avatar-' . \Illuminate\Support\Str::uuid();
@endphp
<span {{ $attributes->class(['relative inline-flex shrink-0 align-middle']) }}
    @if ($showStatus && $statusColor) data-status="{{ $status }}" @endif>
    @if ($avatar)
        <img id="{{ $avatarId }}" src="{{ $avatar }}" alt="{{ $imageAlt }}" width="80" height="80"
            loading="lazy" decoding="async" referrerpolicy="no-referrer"
            x-on:error="$el.hidden = true; $el.nextElementSibling.hidden = false"
            class="{{ $avatarSize }} {{ $avatarShape }} border border-border bg-surface object-cover">
        <span hidden role="img" aria-label="Avatar {{ $name }}"
            class="{{ $avatarSize }} {{ $avatarShape }} inline-flex items-center justify-center border border-primary/20 bg-primary/10 font-bold text-primary">
            {{ $initials ?: 'U' }}
        </span>
    @else
        <span role="img" aria-label="Avatar {{ $name }}"
            class="{{ $avatarSize }} {{ $avatarShape }} inline-flex items-center justify-center border border-primary/20 bg-primary/10 font-bold text-primary">
            {{ $initials ?: 'U' }}
        </span>
    @endif
    @if ($showStatus && $statusColor)
        <span class="absolute bottom-0 right-0 size-3 rounded-full border-2 border-surface {{ $statusColor }}"
            role="img" aria-label="Status {{ $status }}"></span>
    @endif
</span>
