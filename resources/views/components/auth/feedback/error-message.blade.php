@props([
    'message' => null,
    'title' => 'Terjadi Kesalahan',
    'variant' => 'inline',
    'dismissible' => false,
    'icon' => true,
    'id' => null,
])
@php
    $message = is_string($message) ? trim($message) : null;
    $title = is_string($title) ? trim($title) : 'Terjadi Kesalahan';
    $variants = [
        'inline' => 'rounded-xl border border-danger/20 bg-danger/10 p-4',
        'subtle' => 'rounded-xl bg-surface-raised p-4',
        'banner' => 'rounded-xl border border-danger/20 bg-danger/10 px-4 py-3',
    ];
    $variantClass = $variants[$variant] ?? $variants['inline'];
    $hasMessage = filled($message) || $slot->isNotEmpty();
@endphp
@if ($hasMessage)
    <div @if ($id) id="{{ $id }}" @endif
        {{ $attributes->class(['relative flex gap-3 text-left', $variantClass]) }} role="alert" aria-live="assertive">
        @if ($icon)
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-5 shrink-0 text-danger"
                aria-hidden="true" />
        @endif
        <div class="min-w-0 flex-1">
            @if ($title !== '' && $variant !== 'banner')
                <p class="text-sm font-semibold text-danger">{{ $title }}</p>
            @endif
            <div class="{{ $variant === 'subtle' ? 'text-muted' : 'text-foreground/90' }} text-sm leading-6">
                @if ($message)
                    {{ $message }}
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
        @if ($dismissible)
            <button type="button" x-data x-on:click="$el.closest('[role=alert]').remove()"
                class="inline-flex size-7 shrink-0 items-center justify-center rounded-lg text-muted transition hover:bg-surface-hover hover:text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                aria-label="Tutup pesan kesalahan">
                <x-shared.icon name="heroicon-o-x-mark" class="size-4" aria-hidden="true" />
            </button>
        @endif
    </div>
@endif
