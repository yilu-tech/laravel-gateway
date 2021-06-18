<?php

namespace YiluTech\Gateway;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::group(Gateway::$defaultRouteOptions, function () {
            Route::get('gateway/apis', [RouteService::class, 'all']);
        });
    }
}
