<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';
    protected $namespaceFoxie = 'Foxie\\Http\\Controllers';
    protected $namespaceIgnite = 'Ignite\\Http\\Controllers';
    protected $namespaceOurProperty = 'OurProperty\\Http\\Controllers';
    protected $namespacePropertyMe = 'PropertyMe\\Http\\Controllers';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::prefix('api/foxie')
            ->middleware('api')
            ->namespace($this->namespaceIgnite)
            ->group(base_path('routes/foxie.php'));

            Route::prefix('api/ignite')
            ->middleware('api')
            ->namespace($this->namespaceFoxie)
            ->group(base_path('routes/ignite.php'));

            Route::prefix('api/our-property')
                ->middleware('api')
                ->namespace($this->namespaceOurProperty)
                ->group(base_path('routes/our-property.php'));

            Route::middleware([])
                ->group(base_path('app/Modules/PropertyMe/route.php'));

            /**
             * HoodLead module
             */
            Route::middleware([])
                ->group(base_path('app/Modules/HoodLead/route.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(500);
        });
    }
}
