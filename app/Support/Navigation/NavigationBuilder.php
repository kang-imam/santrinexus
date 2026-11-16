<?php

namespace App\Support\Navigation;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    ];

    public function get(string $group): array
    {
        if (! in_array($group, $this->groups, true)) {
            return [];
        }
        $items = config("navigation.{$group}", []);
        if (! is_array($items) || $items === []) {
            return [];
        }
        if (array_is_list($items)) {
            $result = $this->processItems($items, $group);
            if ($group === 'footer') {
                return $this->processFooter($result);
            }
            if ($group === 'header') {
                return $this->limitHeaderItems($result);
            }

            return $result;
        }
        $result = [];
        $remaining = max(0, (int) config('navigation.settings.footer_max_items', 0));
        $headerRoutes = $group === 'footer' ? $this->getHeaderRoutes() : [];
        foreach ($items as $label => $children) {
            if (! is_array($children)) {
                continue;
            }
            $processed = $this->processItems($children, $group);
            if ($group === 'footer') {
                $processed = $this->removeDuplicateRoutes($processed, $headerRoutes);
                if ($remaining > 0) {
                    $processed = $this->limitItems($processed, $remaining);
                }
            }
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
        if ($key === '') {
            return null;
        }

        return $this->findInItems($this->get($group), $key);
    }

    protected function findInItems(array $items, string $key): ?array
    {
        foreach ($items as $item) {
            if (($item['key'] ?? null) === $key) {
                return $item;
            }
            foreach (['items', 'children'] as $childKey) {
                if (! isset($item[$childKey]) || ! is_array($item[$childKey])) {
                    continue;
                }
                $found = $this->findInItems($item[$childKey], $key);
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
            $routeName = isset($item['route']) && is_string($item['route']) ? $item['route'] : null;
            $children = isset($item['children']) && is_array($item['children'])
                ? $this->processItems($item['children'], $group)
                : [];
            if ($routeName === null && $children === []) {
                continue;
            }
            $url = null;
            if ($routeName !== null) {
                $routeExists = Route::has($routeName);
                if (! $routeExists && config('navigation.settings.filter_missing_routes', true)) {
                    $routeName = null;
                    if ($children === []) {
                        continue;
                    }
                } elseif ($routeExists) {
                    $parameters = $item['parameters'] ?? [];
                    if (! is_array($parameters)) {
                        $parameters = [];
                    }
                    $hasParameters = $this->hasRequiredParameters($routeName, $parameters);
                    if (! $hasParameters && config('navigation.settings.hide_dynamic_routes_without_parameters', true)) {
                        $routeName = null;
                        if ($children === []) {
                            continue;
                        }
                    } elseif ($hasParameters) {
                        try {
                            $url = route($routeName, $parameters);
                        } catch (Throwable) {
                            if (config('navigation.settings.hide_dynamic_routes_without_parameters', true)) {
                                $routeName = null;
                                if ($children === []) {
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
            $active = config('navigation.settings.show_active_state', true)
                ? $this->isActive($item, $routeName)
                : false;
            if (! $active && $children !== []) {
                $active = collect($children)->contains(fn ($child) => $child['active'] ?? false);
            }
            $label = $item['label'] ?? '';
            $result[] = [
                'key' => isset($item['key']) && is_string($item['key']) ? $item['key'] : null,
                'label' => is_scalar($label) ? (string) $label : '',
                'route' => $routeName,
                'url' => $url,
                'icon' => config('navigation.settings.show_icons', true) ? ($item['icon'] ?? null) : null,
                'active' => $active,
                'visible' => true,
                'target' => isset($item['target']) && is_string($item['target']) ? $item['target'] : null,
                'rel' => isset($item['rel']) && is_string($item['rel']) ? $item['rel'] : null,
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
        if (in_array($group, ['account', 'notifications'], true) && ! Auth::check()) {
            return false;
        }
        if (! config('navigation.settings.filter_by_access', true)) {
            return true;
        }
        $user = Auth::user();
        if (isset($item['permission'])) {
            if (! ($user instanceof User) || ! is_string($item['permission']) || ! $user->can($item['permission'])) {
                return false;
            }
        }
        if (isset($item['role'])) {
            if (! ($user instanceof User) || ! method_exists($user, 'hasAnyRole')) {
                return false;
            }
            $roles = is_array($item['role']) ? $item['role'] : [$item['role']];
            $roles = array_values(array_filter($roles, fn ($role) => is_string($role) && $role !== ''));
            if ($roles === [] || ! $user->hasAnyRole($roles)) {
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
        preg_match_all('/\{([^}?]+)(\?)?\}/', $route->uri(), $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $name = $match[1];
            $optional = ($match[2] ?? '') === '?';
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
        if (! $currentRoute || ! $currentRoute->getName()) {
            return false;
        }
        $patterns = $item['active_routes'] ?? [];
        $patterns = is_string($patterns) ? [$patterns] : $patterns;
        if (! is_array($patterns)) {
            $patterns = [];
        }
        if ($routeName !== null) {
            $patterns[] = $routeName;
        }
        $patterns = array_values(array_filter($patterns, 'is_string'));

        return $patterns !== [] && request()->routeIs(...$patterns);
    }

    protected function getHeaderRoutes(): array
    {
        return $this->collectRoutes($this->processItems(config('navigation.header', []), 'header'));
    }

    protected function collectRoutes(array $items): array
    {
        $routes = [];
        foreach ($items as $item) {
            if (! empty($item['route'])) {
                $routes[] = $item['route'];
            }
            if (! empty($item['children']) && is_array($item['children'])) {
                $routes = array_merge($routes, $this->collectRoutes($item['children']));
            }
        }

        return array_values(array_unique($routes));
    }

    protected function removeDuplicateRoutes(array $items, array $headerRoutes): array
    {
        $result = [];
        foreach ($items as $item) {
            $children = isset($item['children']) && is_array($item['children'])
                ? $this->removeDuplicateRoutes($item['children'], $headerRoutes)
                : [];
            if (($item['route'] ?? null) !== null && in_array($item['route'], $headerRoutes, true)) {
                if ($children === []) {
                    continue;
                }
                $item['route'] = null;
                $item['url'] = null;
            }
            $item['children'] = $children;
            if (($item['route'] ?? null) === null && $item['url'] === null && $children === []) {
                continue;
            }
            $result[] = $item;
        }

        return $result;
    }

    protected function processFooter(array $items): array
    {
        $items = $this->removeDuplicateRoutes($items, $this->getHeaderRoutes());
        $limit = max(0, (int) config('navigation.settings.footer_max_items', 0));

        return $limit > 0 ? $this->limitItems($items, $limit) : $items;
    }

    protected function limitItems(array $items, int &$remaining): array
    {
        $result = [];
        foreach ($items as $item) {
            $hasUrl = ! empty($item['url']);
            if ($hasUrl && $remaining <= 0) {
                continue;
            }
            if ($hasUrl) {
                $remaining--;
            }
            $children = isset($item['children']) && is_array($item['children'])
                ? $this->limitItems($item['children'], $remaining)
                : [];
            $item['children'] = $children;
            if (! $hasUrl && $children === []) {
                continue;
            }
            $result[] = $item;
        }

        return $result;
    }

    protected function limitHeaderItems(array $items): array
    {
        $limit = max(0, (int) config('navigation.settings.header_max_items', 0));

        return $limit > 0 ? array_slice($items, 0, $limit) : $items;
    }
}
