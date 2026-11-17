@props([
    'user' => null,
    'size' => 'md',
    'shape' => 'circle',
    'showStatus' => false,
    'status' => null,
    'alt' => null,
])
@php
    $user = is_object($user) ? $user : auth()->user();
    $name = is_string($user?->name) ? trim($user->name) : 'Pengguna';
    $name = $name !== '' ? $name : 'Pengguna';
    $words = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = collect($words)
        ->take(2)
        ->map(static fn(string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');
    $initials = $initials !== '' ? $initials : 'U';
    $avatar = $user?->avatar_url;
    $avatar = is_string($avatar) ? trim($avatar) : null;
    $avatarScheme = strtolower((string) parse_url($avatar ?? '', PHP_URL_SCHEME));
    $avatarHost = parse_url($avatar ?? '', PHP_URL_HOST);
    $isAbsoluteUrl =
        in_array($avatarScheme, ['http', 'https'], true) &&
        is_string($avatarHost) &&
        $avatarHost !== '' &&
        filter_var($avatar, FILTER_VALIDATE_URL) !== false;
    $isLocalPath =
        is_string($avatar) &&
        str_starts_with($avatar, '/') &&
        !str_starts_with($avatar, '//') &&
        !preg_match('/[\x00-\x1F\x7F\\\\]/', $avatar) &&
        !preg_match('/^[a-z][a-z0-9+.-]*:/i', $avatar);
    $avatar = $isAbsoluteUrl || $isLocalPath ? $avatar : null;
    $sizes = [
        'xs' => ['size-7', 'text-[10px]', 'size-2', 28],
        'sm' => ['size-8', 'text-xs', 'size-2', 32],
        'md' => ['size-9', 'text-sm', 'size-2.5', 36],
        'lg' => ['size-11', 'text-base', 'size-3', 44],
        'xl' => ['size-14', 'text-lg', 'size-3.5', 56],
        '2xl' => ['size-20', 'text-2xl', 'size-4', 80],
    ];
    $shapes = [
        'circle' => 'rounded-full',
        'rounded' => 'rounded-2xl',
        'square' => 'rounded-lg',
    ];
    $size = is_string($size) ? $size : 'md';
    $shape = is_string($shape) ? $shape : 'circle';
    [$avatarSize, $textSize, $statusSize, $dimension] = $sizes[$size] ?? $sizes['md'];
    $avatarShape = $shapes[$shape] ?? $shapes['circle'];
    $statusColors = [
        'online' => 'bg-accent',
        'offline' => 'bg-subtle',
        'busy' => 'bg-danger',
        'away' => 'bg-warning',
    ];
    $status = is_string($status) ? strtolower(trim($status)) : null;
    $statusColor = $statusColors[$status] ?? null;
    $showStatus = filter_var($showStatus, FILTER_VALIDATE_BOOLEAN) && $statusColor !== null;
    $imageAlt = is_string($alt) && trim($alt) !== '' ? trim($alt) : 'Foto profil ' . $name;
    $fallbackClasses = implode(' ', [
        $avatarSize,
        $avatarShape,
        'inline-flex items-center justify-center border border-primary/25 bg-gradient-to-br from-primary/20 via-primary/10 to-secondary/10',
        $textSize,
        'font-bold tracking-wide text-primary shadow-sm shadow-primary/5 ring-2 ring-primary/5',
    ]);
    $imageClasses = implode(' ', [
        $avatarSize,
        $avatarShape,
        'border border-border/80 bg-surface object-cover shadow-sm shadow-black/10 ring-2 ring-transparent transition-all duration-200 group-hover/avatar:border-primary/40 group-hover/avatar:ring-primary/10',
    ]);
@endphp
<span x-data="{ imageFailed: false }" {{ $attributes->class(['group/avatar relative inline-flex shrink-0 align-middle']) }}
    @if ($showStatus) data-status="{{ $status }}" @endif>
    @if ($avatar)
        <img src="{{ $avatar }}" alt="{{ $imageAlt }}" width="{{ $dimension }}" height="{{ $dimension }}"
            loading="lazy" decoding="async" referrerpolicy="no-referrer" x-show="!imageFailed"
            x-on:error="imageFailed = true" class="{{ $imageClasses }}">
        <span x-cloak x-show="imageFailed" role="img" aria-label="Avatar {{ $name }}"
            class="{{ $fallbackClasses }}">{{ $initials }}</span>
    @else
        <span role="img" aria-label="Avatar {{ $name }}"
            class="{{ $fallbackClasses }}">{{ $initials }}</span>
    @endif
    @if ($showStatus)
        <span
            class="absolute bottom-0 right-0 {{ $statusSize }} rounded-full border-2 border-surface shadow-sm {{ $statusColor }}"
            role="img" aria-label="Status {{ $status }}"></span>
    @endif
</span>
