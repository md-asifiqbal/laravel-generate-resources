<?php

namespace Asif160627\GenerateResources;

use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\GenerateResourcesCommand::class,
                Console\Commands\GenerateService::class,
                Console\Commands\GenerateController::class,
                Console\Commands\GenerateRouteCommand::class,
            ]);
            $this->publishes([
                __DIR__ . '/stubs' =>  resource_path('views/vendor/generate-resources/stubs')
            ], 'generate-resources-stubs');

            $this->publishes([
                __DIR__.'/../config/generate-resources.php' => config_path('generate-resources.php'),
            ], 'generate-resources-config');
        }

    }

    public function register()
    {
    }
}
