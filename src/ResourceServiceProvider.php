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
            ]);
        }

        $this->publishes([
            __DIR__ . '/stubs' =>  resource_path('views/vendor/generate-resources/stubs')
        ], 'generate-resources-stubs');
    }

    public function register()
    {
    }
}
