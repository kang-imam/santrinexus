<?php

declare(strict_types=1);

namespace App\Support\Navigation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Throwable;

class NavigationBuilder
{
    protected array $defaults = [];

    protected array $settings = [];

    protected array $navigation = [];

    protected int $maximumDepth = 10;

    protected array $routeParameterCache = [];

    protected array $routeExistenceCache = [];

    protected const ALLOWED_METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];

    protected const FORM_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    protected const URL_SCHEMES = ['http', 'https', 'mailto', 'tel', 'sms'];

    public function __construct()
    {
        $navigation = config('navigation', []);
        $this->navigation = is_array($navigation) ? $navigation : [];
        $defaults = $this->navigation['defaults'] ?? [];
        $settings = $this->navigation['settings'] ?? [];
        $this->defaults = is_array($defaults) ? $defaults : [];
        $this->settings = is_array($settings) ? $settings : [];
        $this->maximumDepth = max(1, min(20, (int) ($this->settings['maximum_depth'] ?? 10)));
    }

    public function build(string $section, array $context = []): array
    {
        $items = $this->navigation[$section] ?? [];
        if (! is_array($items)) {
            return [];
        }

        return $this->resolveItems($items, $context);
    }

    public function all(array $context = []): array
    {
        $sections = [];
        foreach ($this->navigation as $section => $items) {
            if (in_array($section, ['defaults', 'settings'], true) || ! is_array($items)) {
                continue;
            }
            $sections[$section] = $this->resolveItems($items, $context);
        }

        return $sections;
    }

    public function get(string $section, string $label, array $context = []): ?array
    {
        return $this->findByLabel($this->build($section, $context), trim($label));
    }

    public function findByRoute(string $section, string $route, array $context = []): ?array
    {
        return $this->findByKey($this->build($section, $context), 'route', trim($route));
    }

    public function url(string $route, array $parameters = [], ?string $fallback = null): ?string
    {
        $route = trim($route);
        if ($route === '' || ! $this->routeExists($route)) {
            return $fallback;
        }
        if (! $this->hasRequiredParameters($this->getRequiredRouteParameters($route), $parameters)) {
            return $fallback;
        }
        try {
            return route($route, $parameters);
        } catch (Throwable) {
            return $fallback;
        }
    }

    public function routeExists(?string $route): bool
    {
        if (! is_string($route) || trim($route) === '') {
            return false;
        }
        $route = trim($route);
        if (array_key_exists($route, $this->routeExistenceCache)) {
            return $this->routeExistenceCache[$route];
        }
        if (! ($this->settings['check_route_exists'] ?? true)) {
            return $this->routeExistenceCache[$route] = true;
        }

        return $this->routeExistenceCache[$route] = Route::has($route);
    }

    public function canAccess(array $item): bool
    {
        if (! $this->passesAuthentication($item['auth'] ?? null)) {
            return false;
        }
        if (! $this->passesPermissions($item)) {
            return false;
        }
        $ability = $item['can'] ?? null;
        if (is_string($ability) && trim($ability) !== '') {
            $arguments = $item['can_parameters'] ?? [];
            $arguments = is_array($arguments) ? $arguments : [$arguments];
            try {
                if (! Gate::check(trim($ability), $arguments)) {
                    return false;
                }
            } catch (Throwable) {
                return false;
            }
        }

        return true;
    }

    public function isActive(array $item): bool
    {
        if (! ($item['active'] ?? true)) {
            return false;
        }
        $route = $item['route'] ?? null;
        if (is_string($route) && trim($route) !== '' && request()->routeIs(trim($route))) {
            return true;
        }
        $url = $item['url'] ?? null;
        if (! is_string($url) || trim($url) === '' || $this->isExternalUrl($url)) {
            return false;
        }
        $targetPath = $this->normalizePath((string) parse_url($url, PHP_URL_PATH));
        $currentPath = $this->normalizePath(request()->path());
        if ($targetPath === '') {
            return $currentPath === '';
        }

        return $currentPath === $targetPath || str_starts_with($currentPath, $targetPath.'/');
    }

    public function isExternalUrl(?string $url): bool
    {
        if (! is_string($url) || trim($url) === '') {
            return false;
        }
        $url = trim($url);
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if (in_array($scheme, ['mailto', 'tel', 'sms'], true)) {
            return true;
        }
        $host = parse_url($url, PHP_URL_HOST);
        if (! is_string($host) || $host === '') {
            return $scheme !== '' || str_starts_with($url, '//');
        }

        return strtolower(rtrim($host, '.')) !== strtolower(rtrim(request()->getHost(), '.'));
    }

    protected function resolveItems(array $items, array $context = [], int $depth = 0): array
    {
        if ($depth >= $this->maximumDepth) {
            return [];
        }
        $resolved = [];
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $item = array_replace($this->defaults, $item);
            if (! $this->isVisible($item['visible'] ?? true)) {
                continue;
            }
            if (! $this->canAccess($item)) {
                continue;
            }
            $children = $item['children'] ?? [];
            $item['children'] = is_array($children) ? $this->resolveItems($children, $context, $depth + 1) : [];
            $item = $this->resolveItem($item, $context);
            if ($item === null) {
                continue;
            }
            if ($item['children'] !== []) {
                $item['active'] = $item['active'] || collect($item['children'])->contains(
                    fn (array $child): bool => (bool) ($child['active'] ?? false)
                );
            }
            if ($item['children'] === [] && empty($item['url']) && ! ($item['requires_form'] ?? false)) {
                continue;
            }
            $resolved[] = $item;
        }

        return array_values($resolved);
    }

    protected function resolveItem(array $item, array $context = []): ?array
    {
        $routeName = $item['route'] ?? null;
        $routeName = is_string($routeName) ? trim($routeName) : null;
        $itemParameters = $item['parameters'] ?? [];
        $contextParameters = $context['parameters'] ?? [];
        $parameters = array_replace(
            is_array($itemParameters) ? $itemParameters : [],
            is_array($contextParameters) ? $contextParameters : []
        );
        $requiredParameters = $item['requires_parameters'] ?? [];
        $requiredParameters = is_array($requiredParameters) ? $requiredParameters : [];
        $routeExists = $routeName !== null && $routeName !== '' ? $this->routeExists($routeName) : false;
        if ($routeName !== null && $routeName !== '' && ! $routeExists) {
            return null;
        }
        $routeParameters = $routeExists ? $this->getRequiredRouteParameters($routeName) : [];
        $requiredParameters = array_values(array_unique(array_merge($requiredParameters, $routeParameters)));
        $hasParameters = $this->hasRequiredParameters($requiredParameters, $parameters);
        $url = null;
        if ($routeName !== null && $routeName !== '' && $routeExists && $hasParameters) {
            $fallback = $this->resolveUrlTemplate($item['url'] ?? null, $parameters);
            $url = $this->url($routeName, $parameters, $fallback);
        } elseif ($routeName === null || $routeName === '') {
            $url = $this->resolveUrlTemplate($item['url'] ?? null, $parameters);
            $hasParameters = $url !== null;
        }
        $method = strtoupper(trim((string) ($item['method'] ?? 'GET')));
        if (! in_array($method, self::ALLOWED_METHODS, true)) {
            $method = 'GET';
        }
        $external = $this->isExternalUrl($url);
        $requiresForm = in_array($method, self::FORM_METHODS, true);
        $navigable = (bool) ($item['navigable'] ?? true)
            && in_array($method, ['GET', 'HEAD'], true)
            && is_string($url)
            && $url !== ''
            && $hasParameters;
        $defaultTarget = $this->defaults['target'] ?? '_self';
        $target = $item['target'] ?? $defaultTarget;
        $allowedTargets = ['_self', '_blank', '_parent', '_top'];
        $target = is_string($target) && in_array($target, $allowedTargets, true) ? $target : '_self';
        $item['parameters'] = $parameters;
        $item['url'] = $url;
        $item['route_exists'] = $routeName !== null && $routeName !== '' ? $routeExists : null;
        $item['parameters_resolved'] = $hasParameters;
        $item['method'] = $method;
        $item['requires_form'] = $requiresForm;
        $item['is_external'] = $external;
        $item['target'] = $external && $target === $defaultTarget
            ? ($this->settings['external_link_target'] ?? '_blank')
            : $target;
        $item['rel'] = $external
            ? ($this->settings['external_link_rel'] ?? 'noopener noreferrer')
            : ($item['rel'] ?? null);
        $item['navigable'] = $navigable;
        $item['active'] = (bool) ($this->settings['show_active_state'] ?? true) && $this->isActive($item);
        $showIcons = (bool) ($this->settings['show_icons'] ?? true);
        $item['icon'] = $showIcons ? ($item['icon'] ?? null) : null;
        $item['icon_fallback'] = $showIcons ? ($item['icon_fallback'] ?? null) : null;
        $item['description'] = (bool) ($this->settings['show_descriptions'] ?? true)
            ? ($item['description'] ?? null)
            : null;
        $item['badge'] = (bool) ($this->settings['show_badges'] ?? true)
            ? ($item['badge'] ?? null)
            : null;

        return $item;
    }

    protected function getRequiredRouteParameters(string $route): array
    {
        if (array_key_exists($route, $this->routeParameterCache)) {
            return $this->routeParameterCache[$route];
        }
        try {
            $registeredRoute = Route::getRoutes()->getByName($route);
            if (! $registeredRoute) {
                return $this->routeParameterCache[$route] = [];
            }
            preg_match_all('/\{([^}?]+)(\?)?\}/', $registeredRoute->uri(), $matches, PREG_SET_ORDER);
            $parameters = [];
            foreach ($matches as $match) {
                if (($match[2] ?? '') === '?') {
                    continue;
                }
                $parameters[] = $match[1];
            }

            return $this->routeParameterCache[$route] = array_values(array_unique($parameters));
        } catch (Throwable) {
            return $this->routeParameterCache[$route] = [];
        }
    }

    protected function hasRequiredParameters(array $required, array $parameters): bool
    {
        foreach ($required as $parameter) {
            if (! is_string($parameter) || ! array_key_exists($parameter, $parameters)) {
                return false;
            }
            if ($parameters[$parameter] === null || $parameters[$parameter] === '') {
                return false;
            }
        }

        return true;
    }

    protected function resolveUrlTemplate(mixed $url, array $parameters = []): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return null;
        }
        $url = trim($url);
        if (preg_match('/[\x00-\x1F\x7F]/', $url) === 1 || str_contains($url, '\\')) {
            return null;
        }
        if (! str_contains($url, '{')) {
            return $this->isAllowedUrl($url) ? $url : null;
        }
        $resolved = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            function (array $matches) use ($parameters): string {
                $key = $matches[1];
                if (! array_key_exists($key, $parameters) || $parameters[$key] === null || $parameters[$key] === '') {
                    return $matches[0];
                }

                return rawurlencode((string) $parameters[$key]);
            },
            $url
        );
        if (! is_string($resolved) || preg_match('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', $resolved) === 1) {
            return null;
        }

        return $this->isAllowedUrl($resolved) ? $resolved : null;
    }

    protected function isAllowedUrl(string $url): bool
    {
        if (preg_match('/[\x00-\x1F\x7F]/', $url) === 1 || str_contains($url, '\\')) {
            return false;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if ($scheme !== '' && ! in_array($scheme, self::URL_SCHEMES, true)) {
            return false;
        }
        if (in_array($scheme, ['http', 'https'], true)) {
            $host = parse_url($url, PHP_URL_HOST);

            return is_string($host) && $host !== '';
        }
        if (in_array($scheme, ['mailto', 'tel', 'sms'], true)) {
            return true;
        }
        if (str_starts_with($url, '//')) {
            $host = parse_url($url, PHP_URL_HOST);

            return is_string($host) && $host !== '';
        }

        return true;
    }

    protected function passesAuthentication(mixed $auth): bool
    {
        if ($auth === null || $auth === false || $auth === '') {
            return true;
        }
        if ($auth === true || $auth === 'auth') {
            return Auth::check();
        }
        if ($auth === 'guest') {
            return Auth::guest();
        }
        if (is_string($auth)) {
            try {
                return Auth::guard($auth)->check();
            } catch (Throwable) {
                return false;
            }
        }

        return false;
    }

    protected function passesPermissions(array $item): bool
    {
        if (! ($this->settings['check_permissions'] ?? true)) {
            return true;
        }
        $permissions = $item['permissions'] ?? [];
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        if (! is_array($permissions) || $permissions === []) {
            return true;
        }
        if (! Auth::check()) {
            return false;
        }
        $results = [];
        foreach ($permissions as $permission) {
            if (! is_string($permission) || trim($permission) === '') {
                $results[] = false;

                continue;
            }
            try {
                $results[] = Gate::check(trim($permission));
            } catch (Throwable) {
                $results[] = false;
            }
        }
        $mode = ($item['permission_mode'] ?? 'all') === 'any' ? 'any' : 'all';

        return $mode === 'any'
            ? in_array(true, $results, true)
            : ! in_array(false, $results, true);
    }

    protected function isVisible(mixed $visible): bool
    {
        if (is_bool($visible)) {
            return $visible;
        }
        if (is_numeric($visible)) {
            return (bool) $visible;
        }
        if (is_string($visible)) {
            return filter_var($visible, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        }

        return false;
    }

    protected function normalizePath(string $path): string
    {
        return trim(rawurldecode($path), '/');
    }

    protected function findByLabel(array $items, string $label): ?array
    {
        foreach ($items as $item) {
            if (($item['label'] ?? null) === $label) {
                return $item;
            }
            if (! empty($item['children'])) {
                $found = $this->findByLabel($item['children'], $label);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        return null;
    }

    protected function findByKey(array $items, string $key, string $value): ?array
    {
        foreach ($items as $item) {
            if (($item[$key] ?? null) === $value) {
                return $item;
            }
            if (! empty($item['children'])) {
                $found = $this->findByKey($item['children'], $key, $value);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        return null;
    }
}
