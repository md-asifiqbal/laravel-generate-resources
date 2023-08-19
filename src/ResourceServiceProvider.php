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
            ]);
        }
    }

    public function register()
    {
    }
}
