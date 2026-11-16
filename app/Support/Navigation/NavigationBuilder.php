<?php

namespace App\Support\Navigation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Throwable;

class NavigationBuilder
{
    protected array $groups = [
        'header',
        'footer',
        'account',
        'auth',
        'notifications',
        'content',
        'contact',
        'newsletter',
        'cookies',
        'invitation',
        'system',
        'legal',
        'settings',
    ];

    public function get(string $group): array
    {
        $items = config("navigation.{$group}", []);
        if (! is_array($items) || $items === []) {
            return [];
        }
        if (array_is_list($items)) {
            return $this->processItems($items, $group);
        }
        $result = [];
        foreach ($items as $label => $children) {
            if (! is_array($children)) {
                continue;
            }
            $processed = $this->processItems($children, $group);
            if ($processed === []) {
                continue;
            }
            $result[] = [
                'label' => (string) $label,
                'items' => $processed,
            ];
        }

        return $result;
    }

    public function all(): array
    {
        $result = [];
        foreach ($this->groups as $group) {
            $result[$group] = $this->get($group);
        }

        return $result;
    }

    public function find(string $group, string $key): ?array
    {
        $items = $this->get($group);

        return $this->findInItems($items, $key);
    }

    protected function findInItems(array $items, string $key): ?array
    {
        foreach ($items as $item) {
            if (($item['key'] ?? null) === $key) {
                return $item;
            }
            if (isset($item['items']) && is_array($item['items'])) {
                $found = $this->findInItems($item['items'], $key);
                if ($found !== null) {
                    return $found;
                }
            }
            if (isset($item['children']) && is_array($item['children'])) {
                $found = $this->findInItems($item['children'], $key);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        return null;
    }

    protected function processItems(array $items, string $group): array
    {
        $result = [];
        foreach ($items as $item) {
            if (! is_array($item) || ! $this->isVisible($item, $group)) {
                continue;
            }
            $routeName = $item['route'] ?? null;
            $children = isset($item['children']) && is_array($item['children'])
                ? $this->processItems($item['children'], $group)
                : [];
            if ($routeName !== null && (! is_string($routeName) || ! Route::has($routeName))) {
                continue;
            }
            if ($routeName === null && $children === []) {
                continue;
            }
            $url = null;
            $active = $this->isActive($item, $routeName);
            if ($routeName !== null) {
                $parameters = $item['parameters'] ?? [];
                if (! is_array($parameters) || ! $this->hasRequiredParameters($routeName, $parameters)) {
                    if ($children === []) {
                        continue;
                    }
                } else {
                    try {
                        $url = route($routeName, $parameters);
                    } catch (Throwable) {
                        if ($children === []) {
                            continue;
                        }
                    }
                }
            }
            if (! $active && $children !== []) {
                $active = collect($children)->contains(fn ($child) => $child['active'] ?? false);
            }
            $result[] = [
                'key' => $item['key'] ?? null,
                'label' => $item['label'] ?? '',
                'route' => $routeName,
                'url' => $url,
                'icon' => $item['icon'] ?? null,
                'active' => $active,
                'visible' => true,
                'target' => $item['target'] ?? null,
                'rel' => $item['rel'] ?? null,
                'badge' => $item['badge'] ?? null,
                'children' => $children,
            ];
        }

        return $result;
    }

    protected function isVisible(array $item, string $group): bool
    {
        if (($item['visible'] ?? true) === false) {
            return false;
        }
        if (($item['auth'] ?? false) && ! Auth::check()) {
            return false;
        }
        if (($item['guest'] ?? false) && Auth::check()) {
            return false;
        }
        if (! config('navigation.settings.filter_by_access', true)) {
            return true;
        }
        if (in_array($group, ['account', 'notifications'], true) && ! Auth::check()) {
            return false;
        }
        if (isset($item['permission']) && ! Gate::allows($item['permission'])) {
            return false;
        }
        if (isset($item['role'])) {
            $user = Auth::user();
            if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole($item['role'])) {
                return false;
            }
        }

        return true;
    }

    protected function hasRequiredParameters(string $routeName, array $parameters): bool
    {
        $route = Route::getRoutes()->getByName($routeName);
        if (! $route) {
            return false;
        }
        preg_match_all('/\{([^}]+)\}/', $route->uri(), $matches);
        foreach ($matches[1] as $parameter) {
            $optional = str_ends_with($parameter, '?');
            $name = rtrim($parameter, '?');
            if ($optional) {
                continue;
            }
            if (! array_key_exists($name, $parameters) || $parameters[$name] === null || $parameters[$name] === '') {
                return false;
            }
        }

        return true;
    }

    protected function isActive(array $item, ?string $routeName): bool
    {
        $currentRoute = request()->route();
        if (! $currentRoute) {
            return false;
        }
        $currentName = $currentRoute->getName();
        if (! $currentName) {
            return false;
        }
        $patterns = $item['active_routes'] ?? [];
        if (is_string($patterns)) {
            $patterns = [$patterns];
        }
        if ($routeName !== null) {
            $patterns[] = $routeName;
        }
        $patterns = array_filter($patterns, 'is_string');

        return $patterns !== [] && request()->routeIs(...$patterns);
    }
}
