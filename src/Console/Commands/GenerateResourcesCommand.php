<?php

namespace Asif160627\GenerateResources\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class GenerateResourcesCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:resource {name} {--type=page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Custom Service Class, Controller, Resource , Model and Migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'page';
        $name = $this->argument('name');
        $modelName = Str::studly(class_basename($this->argument('name')));

        $this->call('make:model', [
            'name' => $modelName,
            '--migration' => true,
        ]);

        $this->call('generate:service', [
            'name' => $name . 'Service',
            '--model' => $modelName,
        ]);

        $this->call('make:request', [
            'name' => "{$name}Request",
        ]);

        // $filename = $type == 'modal' ? 'controller.custom.modal.stub' : 'controller.custom.stub';
        // $sourcePath = $this->getCustomControllerStub();
        // $destinationPath = base_path('vendor\laravel\framework\src\Illuminate\Routing\Console/stubs/' . $filename);
        // if (File::exists($sourcePath)) {
        //     File::copy($sourcePath, $destinationPath);
        // }

        $this->call('generate:plain-controller', array_filter([
            'name' => "{$name}Controller",
            '--type' => $type == 'modal' ? 'modal' : 'page',
            '--service' => "{$name}Service",
            '--requests' => "{$name}Request",
            '--model' => $modelName,
        ]));


        $this->call('generate:route', [
            'route' => Str::plural(Str::kebab($modelName)),
            '--controller' => "{$name}Controller",
        ]);

        $this->call('make:resource', [
            'name' => "{$name}Resource",
        ]);
        $this->info('Resource generated successfully.');
    }



    protected function getStub()
    {
    }

    public function getCustomControllerStub()
    {
        $type = $this->option('type') ?? 'page';
        if ($type == 'modal') {
            return __DIR__ . '/../../stubs/controller.custom.modal.stub';
        }
        return __DIR__ . '/../../stubs/controller.custom.stub';
    }
}
