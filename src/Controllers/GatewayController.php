<?php

namespace YiluTech\Gateway\Controllers;

use YiluTech\Gateway\Services\ApisService;

class GatewayController
{
    public function getApis(ApisService $apisService)
    {
        return $apisService->getApis();
    }
}
