@props([
    'size' => 'lg',
    'showName' => true,
    'showTagline' => true,
    'tagline' => 'Core Digital Ecosystem',
    'alignment' => 'center',
    'description' => null,
    'homeUrl' => null,
])
@php
    $alignments = [
        'left' => [
            'container' => 'items-start text-left',
            'brand' => 'justify-start',
        ],
        'center' => [
            'container' => 'items-center text-center',
            'brand' => 'justify-center',
        ],
    ];
    $alignmentStyles = $alignments[$alignment] ?? $alignments['center'];
    $description = is_string($description) ? trim($description) : '';
    $homeUrl =
        is_string($homeUrl) && trim($homeUrl) !== ''
            ? $homeUrl
            : (\Illuminate\Support\Facades\Route::has('home')
                ? route('home')
                : url('/'));
@endphp
<div {{ $attributes->class(['flex min-w-0 flex-col gap-4', $alignmentStyles['container']]) }}>
    <a href="{{ $homeUrl }}" aria-label="SantriNexus - Beranda" title="Kembali ke Beranda SantriNexus"
        class="group inline-flex max-w-full rounded-xl transition-opacity duration-200 hover:opacity-85 active:opacity-70 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
        <x-shared.brand :size="$size" :show-name="$showName" :show-tagline="$showTagline" :tagline="$tagline" :class="$alignmentStyles['brand']" />
    </a>
    @if ($description !== '')
        <p class="max-w-sm text-sm leading-6 text-muted text-pretty">
            {{ $description }}
        </p>
    @endif
</div>
