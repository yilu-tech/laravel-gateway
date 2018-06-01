<?php

namespace YiluTech\Gateway\Controllers;

use Illuminate\Routing\Controller as BaseController;
use YiluTech\Gateway\Services\ApisService;

class GatewayController extends BaseController
{
    public function getApis(ApisService $apisService)
    {
        return $apisService->getApis();
    }
}
