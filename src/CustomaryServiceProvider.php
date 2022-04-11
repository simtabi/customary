<?php

namespace Simtabi\Customary;

use Illuminate\Support\ServiceProvider;
use Simtabi\Customary\Contracts\CustomaryInterface;
use Simtabi\Customary\Services\CustomaryService;

class CustomaryServiceProvider extends ServiceProvider
{

    private string $packageName = 'customary';
    public  const  PACKAGE_PATH = __DIR__.'/../';

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->loadTranslationsFrom(self::PACKAGE_PATH . "resources/lang/", $this->packageName);
        $this->loadMigrationsFrom(self::PACKAGE_PATH.'database/migrations');
        $this->loadViewsFrom(self::PACKAGE_PATH . "resources/views", $this->packageName);
        $this->mergeConfigFrom(self::PACKAGE_PATH . "config/config.php", $this->packageName);

        $this->app->bind(CustomaryInterface::class, CustomaryService::class);
    }


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConsoles();
    }


    private function registerConsoles(): static
    {
        if ($this->app->runningInConsole())
        {
            $this->publishes([
                self::PACKAGE_PATH . "config/config.php"               => config_path("{$this->packageName}.php"),
            ], "{$this->packageName}:config");

            $this->publishes([
                self::PACKAGE_PATH . "public"                          => public_path("vendor/{$this->packageName}"),
            ], "{$this->packageName}:assets");

            $this->publishes([
                self::PACKAGE_PATH . "resources/views"                 => resource_path("views/vendor/{$this->packageName}"),
            ], "{$this->packageName}:views");

            $this->publishes([
                self::PACKAGE_PATH . "resources/lang"                  => $this->app->langPath("vendor/{$this->packageName}"),
            ], "{$this->packageName}:translations");

            $this->publishes([
                self::PACKAGE_PATH . "database/migrations"             => database_path('/migrations/'),
            ], "{$this->packageName}:migrations");
        }

        return $this;
    }

}
