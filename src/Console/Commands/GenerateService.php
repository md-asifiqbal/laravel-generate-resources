<?php

namespace Asif160627\GenerateResources\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class GenerateService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:service {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     */
    /**
     * Get the main service stub.
     */
    protected function getStub(): string
    {
        $publishedPath = resource_path('views/vendor/generate-resources/stubs/service.stub');

        if (file_exists($publishedPath)) {
            return $publishedPath;
        }
        return __DIR__ . '/../../stubs/service.stub';
    }

    /**
     * Get the method stub.
     */
    protected function getMethodStub(): string
    {
        // Define the path to the published service-method stub
        $publishedPath = resource_path('views/vendor/generate-resources/stubs/service-method.stub');

        // Check if the custom published service-method stub exists
        if (file_exists($publishedPath)) {
            return $publishedPath;
        }

        // Fallback to the default package service-method stub
        return __DIR__ . '/../../stubs/service-method.stub';
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service']
        ];
    }

    protected function buildClass($name): string
    {
        $replace = [];
        $model = $this->option('model');
        $replace['{{ modelNamespace }}'] = isset($model) ? 'use ' . $this->qualifyModel($model) . ';' : '';
        $replace['{{ methods }}'] = $this->getMethods() ?? '';

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function getMethods()
    {
        $methods = '';
        $replace = [];
        $model = $this->option('model');
        if (isset($model)) {
            $replace['{{ model }}'] = $model;
            $methods = str_replace(array_keys($replace), array_values($replace), $this->files->get($this->getMethodStub()));
        }
        return $methods;
    }
}
