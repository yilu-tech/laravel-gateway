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
            $identifier = null;
            $action = $route->getAction();

            if(isset($action['name_prefix']) && is_string($action['name_prefix']) && isset($action['as'])){
               $identifier = $action['name_prefix'] . $action['as'];
            }
            else if(isset($action["as"])){
               $identifier = $action['as'];
            }


            if (isset($identifier) && strrpos($identifier,'@') > -1) {

                $name = explode("@",$identifier);
                if(count($name) > 2){
                    throw  new \Exception('api: '.$route->uri().'  name define error -> '. $identifier);
                }
                $authString = $name[0];           
                $newRoute = [
                    "path" => "/".$route->uri(),
                    "method" => $route->methods()[0],
                    "name" => $name[1],
                ];
                
                if(stripos($newRoute["name"],'!') === 0){
                    $newRoute["name"] = str_replace('!',"",$newRoute["name"]);
                    $newRoute["rbac_ignore"]  = true;
                }

                if($authString == '*'){
                    $newRoute['auth'] = '*';
                }else{
                    $newRoute['auth'] = explode("|", $name[0]);
                }
                array_push($apis, $newRoute);
            }
        }
        return $apis;
    }
}
