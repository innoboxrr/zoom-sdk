<?php

namespace Innoboxrr\ZoomSdk\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        
        $this->mergeConfigFrom(__DIR__ . '/../../config/zoomsdk.php', 'zoomsdk');

        $this->app->singleton('zoom', function ($app) {
            return new \Innoboxrr\ZoomSdk\Zoom\ZoomService();
        });

    }

    public function boot()
    {
        
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'innoboxrrzoomsdk');

        if ($this->app->runningInConsole()) {
            
            // $this->publishes([__DIR__.'/../../resources/views' => resource_path('views/vendor/innoboxrrzoomsdk'),], 'views');

            // $this->publishes([__DIR__.'/../../config/innoboxrrzoomsdk.php' => config_path('innoboxrrzoomsdk.php')], 'config');

        }

    }
    
}