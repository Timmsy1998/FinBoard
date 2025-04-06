<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Jobs\RefreshExchangeRates;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App\Http\Middleware\EnsureSetupIsComplete;
use App\Http\Middleware\CheckRole;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Routing\Router;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        $router->pushMiddlewareToGroup('web', EnsureSetupIsComplete::class);

        Vite::prefetch(concurrency: 3);
        Schedule::command('rates:refresh')->hourly();
        Schedule::job(new RefreshExchangeRates('USD'))->hourly();
    }
}
