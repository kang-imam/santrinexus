@props([
    'methods' => ['authenticator', 'email', 'sms'],
    'selected' => null,
    'enabledMethods' => [],
    'disabledMethods' => [],
    'name' => 'two_factor_method',
    'label' => 'Pilih Metode Verifikasi',
    'description' => 'Pilih metode yang ingin digunakan untuk mengamankan akun Anda.',
    'showTitle' => true,
    'showDescription' => true,
    'layout' => 'vertical',
    'variant' => 'card',
    'size' => 'md',
    'required' => true,
    'disabled' => false,
    'showStatus' => true,
    'showIcons' => true,
    'id' => null,
])
@php
    $methodDefinitions = [
        'authenticator' => [
            'label' => 'Authenticator App',
            'description' => 'Gunakan aplikasi autentikasi untuk menghasilkan kode verifikasi.',
            'icon' => 'heroicon-o-device-phone-mobile',
            'badge' => 'Direkomendasikan',
        ],
        'email' => [
            'label' => 'Email OTP',
            'description' => 'Terima kode verifikasi melalui alamat email terdaftar.',
            'icon' => 'heroicon-o-envelope',
            'badge' => null,
        ],
        'sms' => [
            'label' => 'SMS OTP',
            'description' => 'Terima kode verifikasi melalui nomor telepon terdaftar.',
            'icon' => 'heroicon-o-chat-bubble-left-right',
            'badge' => null,
        ],
        'recovery' => [
            'label' => 'Recovery Codes',
            'description' => 'Gunakan kode pemulihan ketika metode verifikasi utama tidak tersedia.',
            'icon' => 'heroicon-o-key',
            'badge' => 'Cadangan',
        ],
    ];
    $availableMethods = collect($methods)
        ->filter(fn($method) => isset($methodDefinitions[$method]))
        ->unique()
        ->values();
    $enabledMethods = collect($enabledMethods)->intersect($availableMethods)->values()->all();
    $disabledMethods = collect($disabledMethods)->intersect($availableMethods)->values()->all();
    $selectedMethod = old($name, $selected);
    if (!in_array($selectedMethod, $availableMethods->all(), true)) {
        $selectedMethod = null;
    }
    $componentId = $id ?: 'two-factor-methods-' . \Illuminate\Support\Str::uuid();
    $layouts = [
        'vertical' => 'grid grid-cols-1 gap-3',
        'horizontal' => 'grid grid-cols-1 gap-3 sm:grid-cols-2',
    ];
    $variants = [
        'card' => 'rounded-xl border border-border bg-surface-raised p-4',
        'subtle' => 'rounded-xl border border-border/70 bg-surface-raised/40 p-3',
        'plain' => 'rounded-lg border border-border p-3',
    ];
    $sizes = [
        'sm' => ['icon' => 'size-8', 'title' => 'text-xs', 'description' => 'text-[11px]', 'padding' => 'p-3'],
        'md' => ['icon' => 'size-10', 'title' => 'text-sm', 'description' => 'text-xs', 'padding' => 'p-4'],
        'lg' => ['icon' => 'size-12', 'title' => 'text-base', 'description' => 'text-sm', 'padding' => 'p-5'],
    ];
    $layoutClass = $layouts[$layout] ?? $layouts['vertical'];
    $variantClass = $variants[$variant] ?? $variants['card'];
    $sizeConfig = $sizes[$size] ?? $sizes['md'];
@endphp
<div id="{{ $componentId }}" x-data="{ selected: @js($selectedMethod) }" {{ $attributes->class(['w-full space-y-4']) }}>
    @if ($showTitle || $showDescription)
        <div class="space-y-1.5">
            @if ($showTitle)
                <h3 class="text-sm font-semibold text-foreground">{{ $label }}</h3>
            @endif
            @if ($showDescription)
                <p class="text-xs leading-5 text-muted">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="{{ $layoutClass }}" role="radiogroup" aria-label="{{ $label }}">
        @foreach ($availableMethods as $method)
            @php
                $definition = $methodDefinitions[$method];
                $isEnabled = in_array($method, $enabledMethods, true);
                $isMethodDisabled = $disabled || in_array($method, $disabledMethods, true);
                $methodId = $componentId . '-' . $method;
            @endphp
            <label for="{{ $methodId }}"
                class="group relative flex {{ $sizeConfig['padding'] }} items-start gap-3 {{ $variantClass }} transition duration-200"
                :class="selected === @js($method) ? 'border-primary bg-primary/5 ring-1 ring-primary/30' :
                    'hover:border-primary/40'"
                @if ($isMethodDisabled) aria-disabled="true" @endif>
                <input id="{{ $methodId }}" type="radio" name="{{ $name }}" value="{{ $method }}"
                    class="peer sr-only" x-model="selected" @checked($selectedMethod === $method) @disabled($isMethodDisabled)
                    @required($required) />
                @if ($showIcons)
                    <span
                        class="{{ $sizeConfig['icon'] }} flex shrink-0 items-center justify-center rounded-xl bg-surface-hover text-muted transition-colors peer-checked:bg-primary/15 peer-checked:text-primary">
                        <x-shared.icon :name="$definition['icon']" class="size-5" aria-hidden="true" />
                    </span>
                @endif
                <span class="min-w-0 flex-1">
                    <span class="flex flex-wrap items-center gap-2">
                        <span
                            class="{{ $sizeConfig['title'] }} font-semibold text-foreground">{{ $definition['label'] }}</span>
                        @if ($definition['badge'])
                            <span
                                class="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-semibold text-primary">{{ $definition['badge'] }}</span>
                        @endif
                        @if ($showStatus && $isEnabled)
                            <span
                                class="rounded-full bg-emerald-400/10 px-2 py-0.5 text-[10px] font-medium text-emerald-400">Aktif</span>
                        @endif
                    </span>
                    <span
                        class="mt-1 block {{ $sizeConfig['description'] }} leading-5 text-muted">{{ $definition['description'] }}</span>
                    @if ($isMethodDisabled)
                        <span class="mt-1 block text-[11px] text-subtle">Metode tidak tersedia</span>
                    @endif
                </span>
                <span
                    class="mt-1 flex size-4 shrink-0 items-center justify-center rounded-full border border-border text-transparent transition peer-checked:border-primary peer-checked:bg-primary peer-checked:text-slate-950">
                    <x-shared.icon name="heroicon-o-check" class="size-3" aria-hidden="true" />
                </span>
            </label>
        @endforeach
    </div>
    <p class="sr-only" aria-live="polite"
        x-text="selected ? `Metode verifikasi dipilih: ${selected}` : 'Belum ada metode verifikasi yang dipilih.'"></p>
</div>
