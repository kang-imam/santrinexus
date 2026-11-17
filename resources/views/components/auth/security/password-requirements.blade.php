@props([
    'name' => 'password',
    'minLength' => 8,
    'requireUppercase' => true,
    'requireLowercase' => true,
    'requireNumber' => true,
    'requireSpecial' => false,
    'showTitle' => true,
    'title' => 'Persyaratan Password',
    'variant' => 'default',
    'compact' => false,
    'id' => null,
])
@php
    $requirements = [
        ['key' => 'length', 'label' => "Minimal {$minLength} karakter", 'enabled' => $minLength > 0],
        ['key' => 'uppercase', 'label' => 'Mengandung huruf besar (A-Z)', 'enabled' => $requireUppercase],
        ['key' => 'lowercase', 'label' => 'Mengandung huruf kecil (a-z)', 'enabled' => $requireLowercase],
        ['key' => 'number', 'label' => 'Mengandung angka (0-9)', 'enabled' => $requireNumber],
        ['key' => 'special', 'label' => 'Mengandung karakter khusus', 'enabled' => $requireSpecial],
    ];
    $activeRequirements = array_values(array_filter($requirements, fn($item) => $item['enabled']));
    $componentId = $id ?: 'password-requirements-' . \Illuminate\Support\Str::uuid();
    $variantClasses = [
        'default' => 'rounded-xl border border-border bg-surface-raised/60 p-4',
        'subtle' => 'rounded-lg bg-surface-raised/40 p-3',
        'plain' => '',
    ];
    $variantClass = $variantClasses[$variant] ?? $variantClasses['default'];
@endphp
<div id="{{ $componentId }}" x-data="{
    password: '',
    checks: {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false,
        special: false
    },
    update(value) {
        this.password = value;
        this.checks.length = value.length >= {{ (int) $minLength }};
        this.checks.uppercase = /[A-Z]/.test(value);
        this.checks.lowercase = /[a-z]/.test(value);
        this.checks.number = /[0-9]/.test(value);
        this.checks.special = /[^A-Za-z0-9]/.test(value);
    },
    get completed() {
        return {{ json_encode(array_column($activeRequirements, 'key')) }}.filter(key => this.checks[key]).length;
    },
    get total() {
        return {{ count($activeRequirements) }};
    },
    get allSatisfied() {
        return this.completed === this.total;
    }
}" x-init="const field = document.querySelector('[name={{ json_encode($name) }}]');
if (field) {
    update(field.value);
    field.addEventListener('input', event => update(event.target.value));
    field.addEventListener('change', event => update(event.target.value));
}"
    {{ $attributes->class(['w-full space-y-3', $variantClass]) }}>
    @if ($showTitle)
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-sm font-semibold text-foreground">{{ $title }}</h3>
            <span class="text-xs font-medium text-muted" x-text="`${completed}/${total} terpenuhi`"
                aria-live="polite"></span>
        </div>
    @endif
    @if (!$compact)
        <div class="h-1.5 overflow-hidden rounded-full bg-surface-hover">
            <div class="h-full rounded-full bg-primary transition-all duration-300"
                :style="`width: ${total ? (completed / total) * 100 : 0}%`"></div>
        </div>
    @endif
    <ul class="space-y-2" aria-label="{{ $title }}">
        @foreach ($activeRequirements as $requirement)
            <li class="flex items-center gap-2.5 text-xs transition-colors duration-200"
                :class="checks.{{ $requirement['key'] }} ? 'text-emerald-400' : 'text-muted'">
                <span
                    class="flex size-4 shrink-0 items-center justify-center rounded-full transition-colors duration-200"
                    :class="checks.{{ $requirement['key'] }} ? 'bg-emerald-400/15 text-emerald-400' :
                        'bg-surface-hover text-subtle'">
                    <x-shared.icon name="heroicon-o-check" class="size-3" x-show="checks.{{ $requirement['key'] }}" />
                    <x-shared.icon name="heroicon-o-minus" class="size-3" x-show="!checks.{{ $requirement['key'] }}" />
                </span>
                <span>{{ $requirement['label'] }}</span>
            </li>
        @endforeach
    </ul>
    <p class="sr-only" role="status" aria-live="polite"
        x-text="allSatisfied ? 'Semua persyaratan password terpenuhi.' : `${completed} dari ${total} persyaratan password terpenuhi.`">
    </p>
</div>
