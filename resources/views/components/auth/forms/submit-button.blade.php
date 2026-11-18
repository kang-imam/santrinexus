@props([
    'type' => 'submit',
    'variant' => 'primary',
    'size' => 'lg',
    'loading' => false,
    'loadingText' => 'Memproses...',
    'disabled' => false,
    'fullWidth' => true,
    'icon' => null,
    'iconRight' => null,
    'showLoadingIcon' => true,
    'loadingOnSubmit' => false,
    'pill' => false,
    'id' => null,
])
@php
    $variants = [
        'primary' =>
            'bg-primary text-slate-950 shadow-lg shadow-primary/10 hover:bg-cyan-300 focus-visible:ring-primary',
        'secondary' =>
            'bg-secondary text-slate-950 shadow-lg shadow-secondary/10 hover:bg-sky-300 focus-visible:ring-secondary',
        'accent' => 'bg-accent text-slate-950 shadow-lg shadow-accent/10 hover:bg-lime-300 focus-visible:ring-accent',
        'danger' => 'bg-danger text-white shadow-lg shadow-danger/10 hover:bg-rose-400 focus-visible:ring-danger',
        'outline' =>
            'border border-border bg-surface-raised text-foreground hover:border-primary/50 hover:bg-surface-hover focus-visible:ring-primary',
        'ghost' => 'bg-transparent text-muted hover:bg-surface-hover hover:text-foreground focus-visible:ring-primary',
    ];
    $sizes = [
        'sm' => 'min-h-9 px-3.5 text-xs',
        'md' => 'min-h-10 px-4 text-sm',
        'lg' => 'min-h-12 px-5 text-sm',
        'xl' => 'min-h-14 px-6 text-base',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['lg'];
    $isDisabled = $disabled || $loading;
    $buttonId = $id ?: 'auth-submit-' . \Illuminate\Support\Str::uuid();
@endphp
<button id="{{ $buttonId }}" type="{{ $type }}" @disabled($isDisabled)
    @if ($loadingOnSubmit) x-bind:disabled="loading || {{ $isDisabled ? 'true' : 'false' }}"
        x-bind:aria-busy="loading ? 'true' : 'false'" @endif
    @if ($loading) aria-busy="true" @endif
    @if ($isDisabled) aria-disabled="true" @endif
    {{ $attributes->except(['class', 'id'])->class([
            'group relative inline-flex items-center justify-center gap-2 overflow-hidden font-semibold tracking-wide transition duration-200 ease-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-background active:scale-[0.98] disabled:pointer-events-none disabled:opacity-50',
            $variantClass,
            $sizeClass,
            $fullWidth ? 'w-full' : 'w-auto',
            $pill ? 'rounded-full' : 'rounded-xl',
        ]) }}>
    @if ($loading)
        @if ($showLoadingIcon)
            <x-shared.icon name="heroicon-o-arrow-path" class="size-4 animate-spin shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $loadingText }}</span>
    @elseif ($loadingOnSubmit)
        <span class="inline-flex items-center justify-center gap-2" x-show="!loading" x-cloak>
            @if ($icon)
                <x-shared.icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />
            @endif
            <span>{{ $slot->isNotEmpty() ? $slot : 'Lanjutkan' }}</span>
            @if ($iconRight)
                <x-shared.icon :name="$iconRight" class="size-4 shrink-0" aria-hidden="true" />
            @endif
        </span>
        <span class="inline-flex items-center justify-center gap-2" x-show="loading" x-cloak>
            @if ($showLoadingIcon)
                <x-shared.icon name="heroicon-o-arrow-path" class="size-4 animate-spin shrink-0" aria-hidden="true" />
            @endif
            <span>{{ $loadingText }}</span>
        </span>
    @else
        @if ($icon)
            <x-shared.icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $slot->isNotEmpty() ? $slot : 'Lanjutkan' }}</span>
        @if ($iconRight)
            <x-shared.icon :name="$iconRight" class="size-4 shrink-0" aria-hidden="true" />
        @endif
    @endif
</button>
