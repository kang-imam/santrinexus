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
        in_array(strtolower((string) parse_url($avatar, PHP_URL_SCHEME)), ['http', 'https'], true)
            ? $avatar
            : null;
    $sizes = [
        'xs' => ['size-7', 'text-[10px]', 'size-2'],
        'sm' => ['size-8', 'text-xs', 'size-2'],
        'md' => ['size-9', 'text-sm', 'size-2.5'],
        'lg' => ['size-11', 'text-base', 'size-3'],
        'xl' => ['size-14', 'text-lg', 'size-3.5'],
        '2xl' => ['size-20', 'text-2xl', 'size-4'],
    ];
    $shapes = [
        'circle' => 'rounded-full',
        'rounded' => 'rounded-2xl',
        'square' => 'rounded-lg',
    ];
    [$avatarSize, $textSize, $statusSize] = $sizes[$size] ?? $sizes['md'];
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
    $fallbackId = 'avatar-fallback-' . \Illuminate\Support\Str::uuid();
@endphp
<span {{ $attributes->class(['group/avatar relative inline-flex shrink-0 align-middle']) }}
    @if ($showStatus && $statusColor) data-status="{{ $status }}" @endif>
    @if ($avatar)
        <img id="{{ $avatarId }}" src="{{ $avatar }}" alt="{{ $imageAlt }}" width="80" height="80"
            loading="lazy" decoding="async" referrerpolicy="no-referrer"
            onerror="this.hidden=true;document.getElementById('{{ $fallbackId }}').hidden=false"
            class="{{ $avatarSize }} {{ $avatarShape }} border border-border/80 bg-surface object-cover shadow-sm shadow-black/10 ring-2 ring-transparent transition-all duration-200 group-hover/avatar:border-primary/40 group-hover/avatar:ring-primary/10">
        <span id="{{ $fallbackId }}" hidden role="img" aria-label="Avatar {{ $name }}"
            class="{{ $avatarSize }} {{ $avatarShape }} inline-flex items-center justify-center border border-primary/25 bg-gradient-to-br from-primary/20 via-primary/10 to-secondary/10 font-bold tracking-wide text-primary shadow-sm shadow-primary/5 ring-2 ring-primary/5">
            {{ $initials ?: 'U' }}
        </span>
    @else
        <span role="img" aria-label="Avatar {{ $name }}"
            class="{{ $avatarSize }} {{ $avatarShape }} inline-flex items-center justify-center border border-primary/25 bg-gradient-to-br from-primary/20 via-primary/10 to-secondary/10 font-bold tracking-wide text-primary shadow-sm shadow-primary/5 ring-2 ring-primary/5 transition-all duration-200 group-hover/avatar:border-primary/40 group-hover/avatar:ring-primary/10">
            {{ $initials ?: 'U' }}
        </span>
    @endif
    @if ($showStatus && $statusColor)
        <span
            class="absolute bottom-0 right-0 {{ $statusSize }} rounded-full border-2 border-surface shadow-sm {{ $statusColor }}"
            role="img" aria-label="Status {{ $status }}"></span>
    @endif
</span>
