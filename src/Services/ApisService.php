<?php

namespace YiluTech\Gateway\Services;

class ApisService
{
    public function getAll()
    {
        $app = app();
        $routes = $app->routes->getRoutes();
        return $routes;
    }

    public function getApis()
    {
        $routes = $this->getAll();
        $apis = [];
        foreach ($routes as $route) {
            if (isset($route->action["as"])) {
                $name = explode("@", $route->action["as"]);
                $newRoute = [
                    "path" => "/".$route->uri,
                    "method" => $route->methods[0],
                    "name" => $name[1],
                    "auth" => explode("|", $name[0]),
                ];
                array_push($apis, $newRoute);
            }
        }
        return $apis;
    }
}
