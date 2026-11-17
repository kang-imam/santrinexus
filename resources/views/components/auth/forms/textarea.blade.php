@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'error' => null,
    'bag' => 'default',
    'size' => 'md',
    'variant' => 'default',
    'rows' => 4,
    'minRows' => 2,
    'maxRows' => 10,
    'minLength' => null,
    'maxLength' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autofocus' => false,
    'autoResize' => false,
    'showCounter' => false,
    'showError' => true,
    'showRequired' => true,
    'id' => null,
])
@php
    $fieldId = $id ?: $name;
    $errorId = $fieldId . '-error';
    $hintId = $fieldId . '-hint';
    $counterId = $fieldId . '-counter';
    $fieldError = $error;
    if ($showError && blank($fieldError)) {
        $fieldError = $errors->getBag($bag)->first($name);
    }
    $hasError = filled($fieldError);
    $sizes = [
        'sm' => 'px-3 py-2.5 text-xs',
        'md' => 'px-4 py-3 text-sm',
        'lg' => 'px-4 py-3.5 text-base',
    ];
    $variants = [
        'default' => 'border-border bg-surface-raised hover:border-primary/40 focus:border-primary',
        'filled' => 'border-transparent bg-surface-hover hover:border-border focus:border-primary',
        'outlined' => 'border-border bg-transparent hover:border-primary/50 focus:border-primary',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $fieldValue = old($name, $value);
    $fieldValue = is_scalar($fieldValue) ? (string) $fieldValue : '';
    $currentLength = mb_strlen($fieldValue);
    $describedBy = collect([
        filled($hint) ? $hintId : null,
        $hasError ? $errorId : null,
        $showCounter && $maxLength ? $counterId : null,
    ])
        ->filter()
        ->implode(' ');
@endphp
<div x-data="{
    count: {{ $currentLength }},
    maxLength: @js($maxLength),
    autoResize: @js($autoResize),
    minRows: {{ max(1, (int) $minRows) }},
    maxRows: {{ max(1, (int) $maxRows) }},
    update(event) {
        this.count = event.target.value.length;
        if (this.autoResize) this.resize(event.target);
    },
    resize(element) {
        element.style.height = 'auto';
        const lineHeight = parseFloat(getComputedStyle(element).lineHeight) || 24;
        const minHeight = lineHeight * this.minRows;
        const maxHeight = lineHeight * this.maxRows;
        element.style.height = Math.min(Math.max(element.scrollHeight, minHeight), maxHeight) + 'px';
        element.style.overflowY = element.scrollHeight > maxHeight ? 'auto' : 'hidden';
    },
    init() {
        if (this.autoResize) this.$nextTick(() => this.resize(this.$refs.field));
    }
}" {{ $attributes->only(['class'])->class(['w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-foreground">
            {{ $label }}
            @if ($required && $showRequired)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="sr-only">Wajib diisi</span>
            @endif
        </label>
    @endif
    <textarea x-ref="field" id="{{ $fieldId }}" name="{{ $name }}" rows="{{ max(1, (int) $rows) }}"
        @if ($minLength !== null) minlength="{{ $minLength }}" @endif
        @if ($maxLength !== null) maxlength="{{ $maxLength }}" @endif
        @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        @if ($hasError) aria-invalid="true" @endif @required($required) @disabled($disabled)
        @readonly($readonly) @autofocus($autofocus) @input="update($event)" @class([
            'block w-full rounded-xl border text-foreground outline-none transition duration-200 placeholder:text-subtle focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-50 read-only:cursor-default read-only:opacity-80',
            $sizeClass,
            $variantClass,
            $hasError
                ? 'border-danger/70 focus:border-danger focus:ring-danger/20'
                : '',
            $autoResize ? 'resize-none' : 'resize-y',
        ])
        {{ $attributes->except(['class', 'id', 'name', 'value', 'rows', 'placeholder'])->whereDoesntStartWith('on') }}>{{ $fieldValue }}</textarea>
    <div class="flex items-start justify-between gap-3">
        @if (filled($hint))
            <p id="{{ $hintId }}" class="text-xs leading-5 text-muted">{{ $hint }}</p>
        @else
            <span></span>
        @endif
        @if ($showCounter && $maxLength)
            <p id="{{ $counterId }}" class="shrink-0 text-xs tabular-nums text-muted" aria-live="polite">
                <span x-text="count">{{ $currentLength }}</span>/<span>{{ $maxLength }}</span>
                <span class="sr-only">karakter</span>
            </p>
        @endif
    </div>
    @if ($hasError)
        <p id="{{ $errorId }}" class="flex items-start gap-1.5 text-xs leading-5 text-danger" role="alert">
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-3.5 shrink-0" aria-hidden="true" />
            <span>{{ $fieldError }}</span>
        </p>
    @endif
</div>
