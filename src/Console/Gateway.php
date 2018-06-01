<?php

namespace YiluTech\Gateway\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use YiluTech\Gateway\Services\ApisService;

class Gateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gateway:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '路由发布';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ApisService $apisService)
    {
        $client = new Client(['timeout' => 2.0]);
        $r = $client->request('GET', config('gateway.publishUrl'), [
            'json' => $apisService->getApis()
        ]);
        echo $r;
    }
}
