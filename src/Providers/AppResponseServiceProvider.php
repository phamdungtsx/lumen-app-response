<?php

namespace Phamdungtsx\Providers;

use Phamdungtsx\Exceptions\AppHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;

class AppResponseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            ExceptionHandler::class,
            AppHandler::class
        );   
    }
}