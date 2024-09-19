<?php

namespace Asif160627\GenerateResources\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateController extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:plain-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $publishedPath = resource_path('views/vendor/generate-resources/stubs');

        // Check if a specific stub type was passed
        if ($type = $this->option('type')) {
            // Use the published stub if it exists, otherwise fall back to the package stub
            if (file_exists($publishedPath . "/controller.{$type}.stub")) {
                return $publishedPath . "/controller.{$type}.stub";
            }

            return __DIR__ . "/../../stubs/controller.{$type}.stub";
        }

        // Use the default stub if no type is specified
        if (file_exists($publishedPath . '/controller.stub')) {
            return $publishedPath . '/controller.stub';
        }

        return __DIR__ . '/../../stubs/controller.stub';
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];
        [$requestClassNamespace, $requestClass] = $this->getClassNamespace($this->option('requests'), 'Request');
        [$serviceClassNamespace, $serviceClass] = $this->getClassNamespace($this->option('service'), 'Service');
        $replace["use {$controllerNamespace}\Controller;\n"] = '';
        $replace['{{ requestClassNamespace }}'] = $requestClassNamespace;
        $replace['{{ requestClass }}'] = $requestClass;
        $replace['{{ serviceClassNamespace }}'] = $serviceClassNamespace;
        $replace['{{ serviceClass }}'] = $serviceClass;
        $replace['{{ serviceClassVariable }}'] = lcfirst($serviceClass);
        $replace['{{ keyword }}'] = Str::singular(Str::ucfirst($this->option('model')));
        $replace['{{ routes }}'] = Str::plural(Str::lower($this->option('model')));
        $replace['{{ permission }}'] = Str::lower(Str::singular($this->option('model')));

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    public function getClassNamespace($name, $type = 'Request')
    {
        if ($type == 'Request') {
            $namespace = 'App\\Http\\Requests\\';
            $namespace .= str_replace('/', '\\', $name);
        } elseif ($type == 'Service') {
            $namespace = 'App\\Services\\';
            $namespace .= str_replace('/', '\\', $name);
        }
        return [$namespace, class_basename($namespace)];
    }


    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @param  string  $modelClass
     * @return array
     */
    protected function buildFormRequestReplacements(array $replace, $modelClass)
    {
        [$namespace, $storeRequestClass, $updateRequestClass] = [
            'Illuminate\\Http',
            'Request',
            'Request',
        ];

        if ($this->option('requests')) {
            $namespace = 'App\\Http\\Requests';

            [$storeRequestClass, $updateRequestClass] = $this->generateFormRequests(
                $modelClass,
                $storeRequestClass,
                $updateRequestClass
            );
        }

        $namespacedRequests = $namespace . '\\' . $storeRequestClass . ';';

        if ($storeRequestClass !== $updateRequestClass) {
            $namespacedRequests .= PHP_EOL . 'use ' . $namespace . '\\' . $updateRequestClass . ';';
        }

        return array_merge($replace, [
            '{{ storeRequest }}' => $storeRequestClass,
            '{{storeRequest}}' => $storeRequestClass,
            '{{ updateRequest }}' => $updateRequestClass,
            '{{updateRequest}}' => $updateRequestClass,
            '{{ namespacedStoreRequest }}' => $namespace . '\\' . $storeRequestClass,
            '{{namespacedStoreRequest}}' => $namespace . '\\' . $storeRequestClass,
            '{{ namespacedUpdateRequest }}' => $namespace . '\\' . $updateRequestClass,
            '{{namespacedUpdateRequest}}' => $namespace . '\\' . $updateRequestClass,
            '{{ namespacedRequests }}' => $namespacedRequests,
            '{{namespacedRequests}}' => $namespacedRequests,
        ]);
    }

    /**
     * Generate the form requests for the given model and classes.
     *
     * @param  string  $modelClass
     * @param  string  $storeRequestClass
     * @param  string  $updateRequestClass
     * @return array
     */
    protected function generateFormRequests($modelClass, $storeRequestClass, $updateRequestClass)
    {
        $storeRequestClass = 'Store' . class_basename($modelClass) . 'Request';

        $this->call('make:request', [
            'name' => $storeRequestClass,
        ]);

        $updateRequestClass = 'Update' . class_basename($modelClass) . 'Request';

        $this->call('make:request', [
            'name' => $updateRequestClass,
        ]);

        return [$storeRequestClass, $updateRequestClass];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['requests', null, InputOption::VALUE_REQUIRED, 'The form requests for the controller'],
            ['service', null, InputOption::VALUE_REQUIRED, 'The service for the controller'],
            ['type', null, InputOption::VALUE_REQUIRED, 'The type of controller being generated (page, modal)'],
            ['model', null, InputOption::VALUE_REQUIRED, 'The model that the controller applies to'],
        ];
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
}
