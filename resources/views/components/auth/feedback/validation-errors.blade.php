@props([
    'title' => 'Periksa Kembali Data Kamu',
    'bag' => 'default',
    'field' => null,
    'variant' => 'inline',
    'dismissible' => false,
    'icon' => true,
    'showCount' => true,
    'id' => null,
])
@php
    $title = is_string($title) ? trim($title) : 'Periksa Kembali Data Kamu';
    $field = is_string($field) ? trim($field) : null;
    $variants = [
        'inline' => 'rounded-xl border border-danger/20 bg-danger/10 p-4',
        'subtle' => 'rounded-xl bg-surface-raised p-4',
        'banner' => 'rounded-xl border border-danger/20 bg-danger/10 px-4 py-3',
    ];
    $variantClass = $variants[$variant] ?? $variants['inline'];
    $errorBag = $errors->getBag($bag);
    $messages = $field ? $errorBag->get($field) : $errorBag->all();
    $messages = collect($messages)
        ->filter(fn($message) => is_string($message) && trim($message) !== '')
        ->map(fn($message) => trim($message))
        ->unique()
        ->values();
    $hasErrors = $messages->isNotEmpty();
@endphp
@if ($hasErrors)
    <div @if ($id) id="{{ $id }}" @endif
        {{ $attributes->class(['relative flex gap-3 text-left', $variantClass]) }} role="alert" aria-live="assertive"
        aria-atomic="true">
        @if ($icon)
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-5 shrink-0 text-danger"
                aria-hidden="true" />
        @endif
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                @if ($title !== '' && $variant !== 'banner')
                    <p class="text-sm font-semibold text-danger">{{ $title }}</p>
                @endif
                @if ($showCount && $messages->count() > 1)
                    <span
                        class="inline-flex items-center rounded-full bg-danger/10 px-2 py-0.5 text-xs font-semibold tabular-nums text-danger">
                        {{ $messages->count() }} kesalahan
                    </span>
                @endif
            </div>
            <ul class="{{ $variant === 'subtle' ? 'text-muted' : 'text-foreground/90' }} mt-1 space-y-1 text-sm leading-6"
                @if ($messages->count() > 1) role="list" @endif>
                @foreach ($messages as $message)
                    <li class="flex items-start gap-2">
                        @if ($messages->count() > 1)
                            <span class="mt-[0.65rem] size-1 shrink-0 rounded-full bg-current"
                                aria-hidden="true"></span>
                        @endif
                        <span>{{ $message }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @if ($dismissible)
            <button type="button" x-data x-on:click="$el.closest('[role=alert]').remove()"
                class="inline-flex size-7 shrink-0 items-center justify-center rounded-lg text-muted transition hover:bg-surface-hover hover:text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                aria-label="Tutup pesan validasi">
                <x-shared.icon name="heroicon-o-x-mark" class="size-4" aria-hidden="true" />
            </button>
        @endif
    </div>
@endif
