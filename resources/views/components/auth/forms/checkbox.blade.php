@props([
    'name',
    'label' => null,
    'description' => null,
    'value' => '1',
    'checked' => false,
    'defaultChecked' => false,
    'error' => null,
    'bag' => 'default',
    'size' => 'md',
    'variant' => 'default',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'showError' => true,
    'id' => null,
])
@php
    $fieldId = $id ?: $name;
    $errorId = $fieldId . '-error';
    $descriptionId = $fieldId . '-description';
    $fieldError = $error;
    if ($showError && blank($fieldError)) {
        $fieldError = $errors->getBag($bag)->first($name);
    }
    $hasError = filled($fieldError);
    $hasDescription = filled($description) || $slot->isNotEmpty();
    $isChecked = old($name, $checked ? $value : ($defaultChecked ? $value : null)) == $value;
    $sizes = [
        'sm' => 'size-3.5',
        'md' => 'size-4',
        'lg' => 'size-5',
    ];
    $variants = [
        'default' => 'rounded-lg',
        'card' => 'rounded-xl border border-border bg-surface-raised p-4 transition hover:border-primary/30',
        'subtle' => 'rounded-lg bg-surface-raised/60 p-3',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $describedBy = collect([$hasDescription ? $descriptionId : null, $hasError ? $errorId : null])
        ->filter()
        ->implode(' ');
    $isDisabled = $disabled;
    $isReadonly = $readonly;
@endphp
<div {{ $attributes->only(['class'])->class(['w-full', $variantClass, $hasError ? 'border-danger/50' : '']) }}>
    <label for="{{ $fieldId }}"
        class="flex items-start gap-3 {{ $isDisabled || $isReadonly ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
        <input id="{{ $fieldId }}" name="{{ $name }}" type="checkbox" value="{{ $value }}"
            @checked($isChecked) @required($required) @disabled($isDisabled)
            @if ($isReadonly) onclick="return false" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            @if ($hasError) aria-invalid="true" @endif
            class="{{ $sizeClass }} mt-0.5 shrink-0 rounded border-border bg-surface-raised text-primary accent-primary transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary focus:ring-0 disabled:cursor-not-allowed" />
        <span class="min-w-0 flex-1">
            @if ($label)
                <span class="block text-sm font-medium leading-5 text-foreground">
                    {{ $label }}
                    @if ($required)
                        <span class="text-danger" aria-hidden="true">*</span>
                        <span class="sr-only">Wajib dipilih</span>
                    @endif
                </span>
            @endif
            @if ($hasDescription)
                <span id="{{ $descriptionId }}" class="mt-1 block text-xs leading-5 text-muted">
                    @if (filled($description))
                        {{ $description }}
                    @else
                        {{ $slot }}
                    @endif
                </span>
            @endif
        </span>
    </label>
    @if ($hasError)
        <p id="{{ $errorId }}" class="mt-2 flex items-start gap-1.5 text-xs leading-5 text-danger" role="alert">
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-3.5 shrink-0" aria-hidden="true" />
            <span>{{ $fieldError }}</span>
        </p>
    @endif
</div>
