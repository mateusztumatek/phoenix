<?php

namespace Mateusztumatek\Monitor;
use Illuminate\Support\ServiceProvider;

class MonitorServiceProvider extends ServiceProvider{
    public function boot(){
        $this->publishes([
            __DIR__.'/../config/monitor.php' => config_path('monitor.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'routes/web.php');
    }

    public function register()
    {
    }
}