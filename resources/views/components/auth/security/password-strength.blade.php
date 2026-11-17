@props([
    'name' => 'password',
    'minLength' => 8,
    'showLabel' => true,
    'showDescription' => true,
    'showScore' => false,
    'showProgress' => true,
    'compact' => false,
    'variant' => 'default',
    'id' => null,
])
@php
    $componentId = $id ?: 'password-strength-' . \Illuminate\Support\Str::uuid();
    $variants = [
        'default' => 'rounded-xl border border-border bg-surface-raised/60 p-4',
        'subtle' => 'rounded-lg bg-surface-raised/40 p-3',
        'plain' => '',
    ];
    $variantClass = $variants[$variant] ?? $variants['default'];
@endphp
<div id="{{ $componentId }}" x-data="{
    password: '',
    score: 0,
    level: 'Belum diisi',
    description: 'Masukkan password untuk memeriksa kekuatannya.',
    color: 'bg-surface-hover',
    textColor: 'text-muted',
    update(value) {
        this.password = value;
        if (!value.length) {
            this.score = 0;
            this.level = 'Belum diisi';
            this.description = 'Masukkan password untuk memeriksa kekuatannya.';
            this.color = 'bg-surface-hover';
            this.textColor = 'text-muted';
            return;
        }
        let points = 0;
        const checks = [
            value.length >= {{ (int) $minLength }},
            value.length >= 12,
            /[a-z]/.test(value) && /[A-Z]/.test(value),
            /[0-9]/.test(value),
            /[^A-Za-z0-9]/.test(value),
            value.length >= 16
        ];
        points = checks.filter(Boolean).length;
        if (value.length < 8) points = Math.min(points, 1);
        this.score = Math.min(points, 5);
        if (this.score <= 1) {
            this.level = 'Lemah';
            this.description = 'Gunakan password yang lebih panjang dan bervariasi.';
            this.color = 'bg-danger';
            this.textColor = 'text-danger';
        } else if (this.score <= 2) {
            this.level = 'Cukup';
            this.description = 'Tambahkan variasi karakter agar lebih sulit ditebak.';
            this.color = 'bg-warning';
            this.textColor = 'text-warning';
        } else if (this.score <= 4) {
            this.level = 'Kuat';
            this.description = 'Password sudah memiliki kombinasi yang baik.';
            this.color = 'bg-secondary';
            this.textColor = 'text-secondary';
        } else {
            this.level = 'Sangat Kuat';
            this.description = 'Password memenuhi indikator kekuatan yang tersedia.';
            this.color = 'bg-accent';
            this.textColor = 'text-accent';
        }
    }
}" x-init="const field = document.querySelector('[name={{ json_encode($name) }}]');
if (field) {
    update(field.value);
    field.addEventListener('input', event => update(event.target.value));
    field.addEventListener('change', event => update(event.target.value));
}"
    {{ $attributes->class(['w-full space-y-3', $variantClass]) }}>
    @if ($showLabel)
        <div class="flex items-center justify-between gap-3">
            <span class="text-xs font-medium text-muted">Kekuatan Password</span>
            <span class="text-xs font-semibold transition-colors duration-200" :class="textColor"
                x-text="level"></span>
        </div>
    @endif
    @if ($showProgress)
        <div class="flex gap-1.5" role="meter" aria-label="Kekuatan password" aria-valuemin="0" aria-valuemax="5"
            x-bind:aria-valuenow="score" x-bind:aria-valuetext="level">
            @for ($i = 1; $i <= 5; $i++)
                <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-surface-hover">
                    <div class="h-full rounded-full transition-all duration-300"
                        :class="score >= {{ $i }} ? color : 'bg-transparent'"
                        :style="score >= {{ $i }} ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            @endfor
        </div>
    @endif
    @if ($showDescription && !$compact)
        <p class="text-xs leading-5 text-muted" x-text="description"></p>
    @endif
    @if ($showScore)
        <p class="text-right text-xs tabular-nums text-subtle" x-text="`${score}/5`"></p>
    @endif
    <p class="sr-only" role="status" aria-live="polite" x-text="`Kekuatan password: ${level}`"></p>
</div>
