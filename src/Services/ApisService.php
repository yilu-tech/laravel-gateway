<?php

namespace YiluTech\Gateway\Services;

class ApisService
{
    public function routes()
    {
        return collect(app()->router->getRoutes());
    }

    public function getApis()
    {
        return $this->routes()->map(function ($route) {

            $name = $this->getName($route);

            if (!$name || count($parts = explode('@', $name)) !== 2) {
                return null;
            }

            $auth = $parts[0];
            $name = $parts[1];

            $rbac_ignore = $name{0} === '!';

            if ($rbac_ignore) {
                $name = substr($name, 1);
            }

            if ($auth !== '*') {
                $auth = explode('|', $auth);
            }

            $path = $this->getUri($route);
            $method = $this->getMethod($route);

            return compact('path', 'method', 'name', 'auth', 'rbac_ignore');
        })->filter()->values();
    }

    protected function getMethod($route)
    {
        return is_array($route) ? $route['method'] : $route->methods()[0];
    }

    protected function getName($route)
    {
        $action = is_array($route) ? $route['action'] : $route->getAction();

        if (empty($action['as'])) {
            return null;
        }

        if (empty($action['name_prefix'])) {
            return $action['as'];
        }

        if (is_array($action['name_prefix'])) {
            $prefix = implode('.', array_map(function ($prefix) {
                return rtrim($prefix, ' .');
            }, $action['name_prefix']));
        } else {
            $prefix = rtrim($action['name_prefix'], ' .');
        }

        return preg_replace('/@\./', '@', $prefix . '.' . $action['as']);
    }

    protected function getUri($route)
    {
        return preg_replace_callback("/\{[\w?]+\}/", function ($str) {
            return ':' . trim($str[0], '{?}');
        }, is_array($route) ? $route['uri'] : ('/' . $route->uri()));
    }
}
