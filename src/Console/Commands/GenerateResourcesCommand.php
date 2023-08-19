<?php

namespace Asif160627\GenerateResources\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class GenerateResourcesCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:resource {name}';

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

        $this->call('make:controller', array_filter([
            'name' => "{$name}Controller"
        ]));

        $this->call('make:resource', [
            'name' => "{$name}Resource",
        ]);
        $this->info('Resource generated successfully.');
    }



    protected function getStub()
    {
    }
}
