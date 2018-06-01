<?php

namespace YiluTech\Gateway;

use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            Console\Gateway::class,
        ]);
    }

    public function register()
    {
        
    }
}