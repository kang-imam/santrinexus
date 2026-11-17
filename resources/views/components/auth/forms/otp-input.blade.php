@props([
    'name' => 'code',
    'length' => 6,
    'label' => 'Kode Verifikasi',
    'description' => 'Masukkan kode verifikasi yang telah dikirimkan kepada Anda.',
    'value' => null,
    'error' => null,
    'bag' => 'default',
    'size' => 'md',
    'autocomplete' => 'one-time-code',
    'autoFocus' => false,
    'autoSubmit' => false,
    'showLabel' => true,
    'showDescription' => true,
    'showError' => true,
    'disabled' => false,
    'required' => true,
    'id' => null,
])
@php
    $length = max(4, min(8, (int) $length));
    $fieldId = $id ?: 'otp-' . \Illuminate\Support\Str::uuid();
    $errorId = $fieldId . '-error';
    $descriptionId = $fieldId . '-description';
    $fieldError = $error;
    if ($showError && blank($fieldError)) {
        $fieldError = $errors->getBag($bag)->first($name);
    }
    $hasError = filled($fieldError);
    $sizes = [
        'sm' => 'h-10 w-9 text-base',
        'md' => 'h-12 w-11 text-lg',
        'lg' => 'h-14 w-12 text-xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $describedBy = collect([
        $showDescription && filled($description) ? $descriptionId : null,
        $hasError ? $errorId : null,
    ])
        ->filter()
        ->implode(' ');
    $initialValue = old($name, $value);
    $initialValue = is_scalar($initialValue)
        ? substr(preg_replace('/\D/', '', (string) $initialValue), 0, $length)
        : '';
@endphp
<div x-data="{
    code: @js($initialValue),
    length: {{ $length }},
    disabled: @js($disabled),
    init() {
        this.code = this.normalize(this.code);
        this.$nextTick(() => {
            this.sync();
            if (@js($autoFocus) && !this.disabled) this.$refs.cells[0]?.focus();
        });
    },
    normalize(value) {
        return String(value ?? '').replace(/\D/g, '').slice(0, this.length);
    },
    sync() {
        this.$refs.hidden.value = this.code;
        this.$refs.hidden.dispatchEvent(new Event('input', { bubbles: true }));
        this.$refs.hidden.dispatchEvent(new Event('change', { bubbles: true }));
        if (@js($autoSubmit) && this.code.length === this.length) {
            this.$nextTick(() => this.$refs.hidden.form?.requestSubmit());
        }
    },
    update(index, value) {
        if (this.disabled) return;
        const digit = this.normalize(value).slice(-1);
        const digits = this.code.padEnd(this.length, ' ').split('');
        digits[index] = digit || ' ';
        this.code = digits.join('').replace(/ /g, '').slice(0, this.length);
        this.sync();
        if (digit && index < this.length - 1) this.$refs.cells[index + 1]?.focus();
    },
    paste(event) {
        if (this.disabled) return;
        const pasted = this.normalize(event.clipboardData.getData('text'));
        if (!pasted) return;
        event.preventDefault();
        this.code = pasted;
        this.sync();
        this.$refs.cells[Math.min(pasted.length, this.length - 1)]?.focus();
    },
    keydown(event, index) {
        if (event.key === 'Backspace') {
            if (this.code[index]) {
                this.update(index, '');
            } else if (index > 0) {
                this.update(index - 1, '');
                this.$refs.cells[index - 1]?.focus();
            }
            event.preventDefault();
        } else if (event.key === 'ArrowLeft' && index > 0) {
            this.$refs.cells[index - 1]?.focus();
            event.preventDefault();
        } else if (event.key === 'ArrowRight' && index < this.length - 1) {
            this.$refs.cells[index + 1]?.focus();
            event.preventDefault();
        } else if (event.key.length === 1 && !/[0-9]/.test(event.key)) {
            event.preventDefault();
        }
    },
    clear() {
        this.code = '';
        this.sync();
        this.$refs.cells[0]?.focus();
    }
}" {{ $attributes->class(['w-full space-y-3']) }}>
    @if ($showLabel)
        <label for="{{ $fieldId }}-0" class="block text-sm font-medium text-foreground">
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif
    @if ($showDescription && filled($description))
        <p id="{{ $descriptionId }}" class="text-xs leading-5 text-muted">{{ $description }}</p>
    @endif
    <input x-ref="hidden" type="hidden" name="{{ $name }}" value="{{ $initialValue }}" @required($required)
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif />
    <div class="flex flex-wrap items-center gap-2" role="group" aria-label="{{ $label }}">
        @for ($index = 0; $index < $length; $index++)
            <input id="{{ $fieldId }}-{{ $index }}" x-ref="cells" type="text" inputmode="numeric"
                pattern="[0-9]*" maxlength="1" autocomplete="{{ $index === 0 ? $autocomplete : 'off' }}"
                aria-label="Digit {{ $index + 1 }} dari {{ $length }}"
                :value="code[{{ $index }}] || ''"
                @input="update({{ $index }}, $event.target.value); $event.target.value = code[{{ $index }}] || ''"
                @keydown="keydown($event, {{ $index }})" @paste="paste($event)" @focus="$event.target.select()"
                @disabled="disabled" @class([
                    $sizeClass,
                    'rounded-xl border bg-surface-raised text-center font-semibold text-foreground outline-none transition duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-50',
                    $hasError
                        ? 'border-danger/70 focus:border-danger focus:ring-danger/20'
                        : 'border-border hover:border-primary/40',
                ]) />
        @endfor
        <button type="button" x-show="code.length > 0 && !disabled" x-cloak @click="clear()"
            class="inline-flex size-9 items-center justify-center rounded-lg text-muted transition hover:bg-surface-hover hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
            aria-label="Hapus kode verifikasi">
            <x-shared.icon name="heroicon-o-x-mark" class="size-4" />
        </button>
    </div>
    @if ($hasError)
        <p id="{{ $errorId }}" class="flex items-start gap-1.5 text-xs leading-5 text-danger" role="alert">
            <x-shared.icon name="heroicon-o-exclamation-circle" class="mt-0.5 size-3.5 shrink-0" aria-hidden="true" />
            <span>{{ $fieldError }}</span>
        </p>
    @endif
</div>
