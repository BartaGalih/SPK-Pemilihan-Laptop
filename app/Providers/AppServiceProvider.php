<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MfepEngine;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MfepEngine::class, fn() => new MfepEngine());
    }

    public function boot()
    {
        //
    }
}
