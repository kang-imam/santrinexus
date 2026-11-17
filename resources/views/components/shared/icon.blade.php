@props([
    'name' => null,
    'fallback' => null,
])
@php
    $normalizeIcon = static function ($icon): ?string {
        if (!is_string($icon)) {
            return null;
        }
        $icon = strtolower(trim($icon));
        return $icon !== '' ? $icon : null;
    };
    $resolveIcon = static function (?string $icon, array $attributes = []): ?string {
        if ($icon === null) {
            return null;
        }
        $candidates = [];
        if (preg_match('/^heroicon-([osc])-([a-z0-9-]+)$/', $icon) === 1) {
            $candidates[] = $icon;
        } elseif (preg_match('/^(?:fa-)?(solid|regular|brands)\s+(fa-[a-z0-9-]+)$/', $icon, $matches) === 1) {
            $prefix = match ($matches[1]) {
                'solid' => 'fas',
                'regular' => 'far',
                'brands' => 'fab',
            };
            $candidates[] = $prefix . '-' . substr($matches[2], 3);
        } elseif (preg_match('/^(fas|far|fab)-([a-z0-9-]+)$/', $icon) === 1) {
            $candidates[] = $icon;
        } elseif (preg_match('/^fa-([a-z0-9-]+)$/', $icon, $matches) === 1) {
            $candidates[] = 'fas-' . $matches[1];
        }
        if ($candidates === []) {
            return null;
        }
        $factory = app(\BladeUI\Icons\Factory::class);
        foreach ($candidates as $candidate) {
            try {
                return $factory->svg($candidate, '', $attributes)->toHtml();
            } catch (\BladeUI\Icons\Exceptions\SvgNotFound) {
                continue;
            } catch (\Throwable) {
                continue;
            }
        }
        return null;
    };
    $name = $normalizeIcon($name);
    $fallback = $normalizeIcon($fallback);
    $rawAttributes = $attributes->getAttributes();
    $safeAttributes = [];
    foreach ($rawAttributes as $key => $value) {
        if (!is_string($key) || preg_match('/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', $key) !== 1) {
            continue;
        }
        if (preg_match('/^on/i', $key) === 1) {
            continue;
        }
        if ($key === 'class' && is_array($value)) {
            $value = \Illuminate\Support\Arr::toCssClasses($value);
        }
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        if (is_scalar($value)) {
            $safeAttributes[$key] = (string) $value;
        }
    }
    $resolvedIcon = $resolveIcon($name, $safeAttributes);
    if ($resolvedIcon === null && $fallback !== $name) {
        $resolvedIcon = $resolveIcon($fallback, $safeAttributes);
    }
@endphp
@if ($resolvedIcon !== null)
    {!! $resolvedIcon !!}
@endif
