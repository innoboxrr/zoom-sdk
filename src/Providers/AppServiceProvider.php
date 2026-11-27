<?php

namespace Innoboxrr\ZoomSdk\Providers;

use Illuminate\Support\ServiceProvider;
use Innoboxrr\ZoomSdk\Zoom\ZoomService;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        $this->mergeConfigFrom(__DIR__ . '/../../config/zoomsdk.php', 'zoomsdk');

        $this->app->singleton('zoom', function ($app) {
            return new ZoomService();
        });

    }

    public function boot(): void
    {

        if ($this->app->runningInConsole()) {

            // `zoomsdk-config` es la etiqueta que documenta el README; `config`
            // se conserva por la convencion del resto del ecosistema. Con
            // `config` a secas se publica la de todos los paquetes a la vez:
            // conviene pasar --provider.
            $this->publishes([
                __DIR__ . '/../../config/zoomsdk.php' => config_path('zoomsdk.php'),
            ], ['zoomsdk-config', 'config']);

        }

    }

}
