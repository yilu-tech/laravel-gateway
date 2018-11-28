<?php

namespace YiluTech\Gateway\Services;

class ApisService
{
    public function routes()
    {
        return collect(app()->routes->getRoutes());
    }

    public function getApis()
    {
        return $this->routes()->map(function ($route) {

            $name = $this->getName($route->getAction());

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

            $path = '/' . $this->getUri($route->uri());
            $method = $route->methods()[0];

            return compact('path', 'method', 'name', 'auth', 'rbac_ignore');
        })->filter()->values();
    }

    protected function getName($action)
    {
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

        return $prefix . '.' . $action['as'];
    }

    protected function getUri($uri)
    {
        return preg_replace_callback('/\\{\\w+\\}?/', function ($str) {
            return ':' . substr($str[0], 1, -1);
        }, $uri);
    }
}
