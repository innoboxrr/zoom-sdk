<?php

namespace Innoboxrr\ZoomSdk\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public function map()
    {

        $this->mapApiRoutes();      

    }

    protected function mapApiRoutes()
    {

        Route::middleware('api')
            ->prefix('zoom')
            ->as('zoom.')
            ->namespace('Innoboxrr\ZoomSdk\Http\Controllers')
            ->group(__DIR__ . '/../../routes/web.php');

    }

}
