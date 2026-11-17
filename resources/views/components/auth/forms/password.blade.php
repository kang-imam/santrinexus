@props([
    'name' => 'password',
    'label' => 'Password',
    'value' => null,
    'placeholder' => 'Masukkan password',
    'hint' => null,
    'autocomplete' => 'current-password',
    'icon' => 'heroicon-o-lock-closed',
    'size' => 'md',
    'variant' => 'default',
    'error' => null,
    'bag' => 'default',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'showToggle' => true,
    'showError' => true,
    'showRequired' => true,
    'showIcon' => true,
    'id' => null,
])
@php
    $fieldId = $id ?: $name;
    $errorId = $fieldId . '-error';
    $hintId = $fieldId . '-hint';
    $fieldError = $error;
    if ($showError && blank($fieldError)) {
        $fieldError = $errors->getBag($bag)->first($name);
    }
    $hasError = filled($fieldError);
    $hasHint = filled($hint);
    $sizes = [
        'sm' => 'min-h-10 px-3 text-xs',
        'md' => 'min-h-12 px-4 text-sm',
        'lg' => 'min-h-14 px-4 text-base',
    ];
    $variants = [
        'default' =>
            'border-border bg-surface-raised focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15',
        'filled' =>
            'border-transparent bg-surface-hover focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15',
        'outlined' =>
            'border-border bg-transparent focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/15',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $describedBy = collect([$hasHint ? $hintId : null, $hasError ? $errorId : null])
        ->filter()
        ->implode(' ');
    $isDisabled = $disabled;
    $isReadonly = $readonly || $isDisabled;
    $showToggle = $showToggle && !$isDisabled && !$isReadonly;
    $componentId = 'password-' . \Illuminate\Support\Str::slug($fieldId) . '-' . \Illuminate\Support\Str::random(6);
@endphp
<div {{ $attributes->only(['class'])->class(['w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="flex items-center gap-1.5 text-sm font-medium text-foreground">
            <span>{{ $label }}</span>
            @if ($required && $showRequired)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="sr-only">Wajib diisi</span>
            @endif
        </label>
    @endif
    <div x-data="{ visible: false }"
        class="relative flex items-center rounded-xl border transition duration-200 {{ $sizeClass }} {{ $hasError ? 'border-danger/70 bg-danger/5 focus-within:border-danger focus-within:ring-2 focus-within:ring-danger/15' : $variantClass }} {{ $isDisabled ? 'cursor-not-allowed opacity-60' : '' }}">
        @if ($showIcon && $icon)
            <x-shared.icon :name="$icon" class="mr-3 size-5 shrink-0 text-muted" aria-hidden="true" />
        @endif
        <input id="{{ $fieldId }}" name="{{ $name }}" type="password"
            x-bind:type="visible ? 'text' : 'password'" value=""
            @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            @if ($hasError) aria-invalid="true" @endif @required($required) @disabled($isDisabled)
            @readonly($isReadonly) @autofocus($autofocus)
            class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-foreground outline-none placeholder:text-subtle focus:ring-0 disabled:cursor-not-allowed disabled:opacity-100 read-only:cursor-default"
            {{ $attributes->except(['class', 'id'])->whereDoesntStartWith('on') }} />
        @if ($showToggle)
            <button type="button" x-on:click="visible = !visible"
                class="ml-3 inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-muted transition hover:bg-surface-hover hover:text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                :aria-label="visible ? 'Sembunyikan password' : 'Tampilkan password'" :aria-pressed="visible.toString()"
                aria-controls="{{ $fieldId }}">
                <x-shared.icon name="heroicon-o-eye" class="size-5" x-show="!visible" aria-hidden="true" />
                <x-shared.icon name="heroicon-o-eye-slash" class="size-5" x-show="visible" x-cloak aria-hidden="true" />
            </button>
        @endif
    </div>
    @if ($hasHint || $hasError)
        <div class="space-y-1">
            @if ($hasHint)
                <p id="{{ $hintId }}" class="text-xs leading-5 text-muted">{{ $hint }}</p>
            @endif
            @if ($hasError)
                <p id="{{ $errorId }}" class="flex items-start gap-1.5 text-xs leading-5 text-danger"
                    role="alert">
                    <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-3.5 shrink-0"
                        aria-hidden="true" />
                    <span>{{ $fieldError }}</span>
                </p>
            @endif
        </div>
    @endif
</div>
