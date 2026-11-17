@props([
    'message' => null,
    'title' => 'Informasi',
    'variant' => 'inline',
    'dismissible' => false,
    'icon' => true,
    'id' => null,
])
@php
    $message = is_string($message) ? trim($message) : null;
    $title = is_string($title) ? trim($title) : 'Informasi';
    $variants = [
        'inline' => 'rounded-xl border border-primary/20 bg-primary/10 p-4',
        'subtle' => 'rounded-xl bg-surface-raised p-4',
        'banner' => 'rounded-xl border border-primary/20 bg-primary/10 px-4 py-3',
    ];
    $variantClass = $variants[$variant] ?? $variants['inline'];
    $hasMessage = filled($message) || trim((string) $slot) !== '';
@endphp
@if ($hasMessage)
    <div @if ($id) id="{{ $id }}" @endif
        {{ $attributes->class(['relative flex gap-3 text-left', $variantClass]) }} role="status" aria-live="polite">
        @if ($icon)
            <x-shared.icon name="heroicon-o-information-circle" class="mt-0.5 size-5 shrink-0 text-primary"
                aria-hidden="true" />
        @endif
        <div class="min-w-0 flex-1">
            @if ($title !== '' && $variant !== 'banner')
                <p class="text-sm font-semibold text-primary">{{ $title }}</p>
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
            <button type="button" x-data x-on:click="$el.closest('[role=status]').remove()"
                class="inline-flex size-7 shrink-0 items-center justify-center rounded-lg text-muted transition hover:bg-surface-hover hover:text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                aria-label="Tutup pesan informasi">
                <x-shared.icon name="heroicon-o-x-mark" class="size-4" aria-hidden="true" />
            </button>
        @endif
    </div>
@endif
