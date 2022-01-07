<?php

namespace Simtabi\Customary;

use Illuminate\Support\ServiceProvider;
use Simtabi\Customary\Contracts\CustomaryInterface;
use Simtabi\Customary\Services\CustomaryService;

class CustomaryServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Load migration
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'customary');

        if ($this->app->runningInConsole())
        {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('/migrations/'),
            ], 'customary:migrations');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('customary.php'),
            ], 'customary:config');
        }

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CustomaryInterface::class, CustomaryService::class);
    }
}
