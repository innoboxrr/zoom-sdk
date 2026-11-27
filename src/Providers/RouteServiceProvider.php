<?php

namespace Innoboxrr\ZoomSdk\Providers;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Hereda de ServiceProvider y no del RouteServiceProvider de Foundation. Aquel
 * guarda en una propiedad estatica de la clase base el cargador de rutas que
 * bootstrap/app.php registra con withRouting(), y cada subclase lo vuelve a
 * ejecutar: la aplicacion cargaba sus routes/web.php y api.php una vez mas por
 * cada paquete.
 *
 * Hoy el paquete no trae rutas. La unica que tenia, GET /zoom/test, creaba una
 * reunion real con las credenciales de la aplicacion y la volcaba con dd() a
 * quien la pidiera, sin autenticacion. Si algun dia hacen falta rutas, van en
 * routes/api/models/*.php y se registran aqui.
 */
class RouteServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // Cuando las rutas estan cacheadas no hay que volver a registrarlas.
        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        $this->mapApiRoutes();
    }

    protected function mapApiRoutes(): void
    {
        foreach (glob(__DIR__ . '/../../routes/api/models/*.php') ?: [] as $file) {

            $name = basename($file, '.php');

            Route::middleware('api')
                ->prefix('api/innoboxrr/zoomsdk/' . $name)
                ->as('api.innoboxrr.zoomsdk.' . $name . '.')
                ->namespace('Innoboxrr\ZoomSdk\Http\Controllers')
                ->group($file);

        }
    }

}
