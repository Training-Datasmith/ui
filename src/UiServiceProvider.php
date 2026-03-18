<?php

declare(strict_types=1);

namespace Laravel\Ui;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Register the package services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuthCommand::class,
                ControllersCommand::class,
                UiCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::mixin(new AuthRouteMethods());
    }
}
