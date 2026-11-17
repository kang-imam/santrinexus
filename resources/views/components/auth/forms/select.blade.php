@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Pilih salah satu',
    'hint' => null,
    'error' => null,
    'bag' => 'default',
    'size' => 'md',
    'variant' => 'default',
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'multiple' => false,
    'showError' => true,
    'showRequired' => true,
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
    $sizes = [
        'sm' => 'min-h-10 px-3 text-xs',
        'md' => 'min-h-12 px-4 text-sm',
        'lg' => 'min-h-14 px-4 text-base',
    ];
    $variants = [
        'default' => 'border-border bg-surface-raised hover:border-primary/40 focus:border-primary',
        'filled' => 'border-transparent bg-surface-hover hover:border-border focus:border-primary',
        'outlined' => 'border-border bg-transparent hover:border-primary/50 focus:border-primary',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $isDisabled = $disabled;
    $isReadonly = $readonly;
    $selectedValue = old($name, $value);
    $selectedValues = $multiple
        ? (is_array($selectedValue)
            ? array_map('strval', $selectedValue)
            : [])
        : [(string) ($selectedValue ?? '')];
    $describedBy = collect([filled($hint) ? $hintId : null, $hasError ? $errorId : null])
        ->filter()
        ->implode(' ');
    $optionGroups = collect($options);
    $isGrouped =
        $optionGroups->isNotEmpty() &&
        collect($optionGroups->first())->isNotEmpty() &&
        is_array($optionGroups->first());
@endphp
<div {{ $attributes->only(['class'])->class(['w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-foreground">
            {{ $label }}
            @if ($required && $showRequired)
                <span class="text-danger" aria-hidden="true">*</span>
                <span class="sr-only">Wajib diisi</span>
            @endif
        </label>
    @endif
    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                <x-shared.icon :name="$icon" class="size-4" aria-hidden="true" />
            </span>
        @endif
        <select id="{{ $fieldId }}" name="{{ $multiple ? $name . '[]' : $name }}"
            @if ($multiple) multiple @endif @required($required) @disabled($isDisabled)
            @if ($isReadonly) onchange="this.value=this.dataset.previousValue" onfocus="this.dataset.previousValue=this.value" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            @if ($hasError) aria-invalid="true" @endif
            @if ($multiple) aria-multiselectable="true" @endif
            @if ($multiple) size="{{ max(3, min(8, count($options))) }}" @endif
            {{ $attributes->except(['class', 'id', 'name', 'value', 'options'])->whereDoesntStartWith('on') }}
            @class([
                'block w-full appearance-none rounded-xl border text-foreground outline-none transition duration-200 focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-50',
                $sizeClass,
                $variantClass,
                $icon ? 'pl-10' : 'pl-4',
                $multiple ? 'pr-4 py-2' : 'pr-11',
                $hasError
                    ? 'border-danger/70 focus:border-danger focus:ring-danger/20'
                    : '',
            ])>
            @if (!$multiple && filled($placeholder))
                <option value="" disabled @selected($selectedValues[0] === '')>{{ $placeholder }}</option>
            @endif
            @if ($isGrouped)
                @foreach ($optionGroups as $groupLabel => $groupOptions)
                    <optgroup label="{{ $groupLabel }}">
                        @foreach ($groupOptions as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected(in_array((string) $optionValue, $selectedValues, true))>{{ $optionLabel }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected(in_array((string) $optionValue, $selectedValues, true))>{{ $optionLabel }}</option>
                @endforeach
            @endif
        </select>
        @unless ($multiple)
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted">
                <x-shared.icon name="heroicon-o-chevron-down" class="size-4" aria-hidden="true" />
            </span>
        @endunless
    </div>
    @if (filled($hint))
        <p id="{{ $hintId }}" class="text-xs leading-5 text-muted">{{ $hint }}</p>
    @endif
    @if ($hasError)
        <p id="{{ $errorId }}" class="flex items-start gap-1.5 text-xs leading-5 text-danger" role="alert">
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-3.5 shrink-0" aria-hidden="true" />
            <span>{{ $fieldError }}</span>
        </p>
    @endif
</div>
