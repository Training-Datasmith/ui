<?php

declare (strict_types=1);
namespace Laravel\Ui;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Service_Provider;
class Ui_Service_Provider extends Service_Provider
{
    /**
     * Register the package services.
     */
    public function register(): void
    {
        if ($this->app->running_in_console()) {
            $this->commands([Auth_Command::class, Controllers_Command::class, Ui_Command::class]);
        }
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::mixin(new Auth_Route_Methods());
    }
}