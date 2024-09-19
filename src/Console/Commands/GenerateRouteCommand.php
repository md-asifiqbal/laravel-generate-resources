<?php

namespace Asif160627\GenerateResources\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateRouteCommand extends Command
{
    protected $signature = 'generate:route {route} {--controller=}';

    protected $description = 'Generate a new route';

    protected $type = 'Route';

    public function handle()
    {
        $route = $this->argument('route');
        $controller = $this->option('controller');
        $controllerNamespace = 'App\\Http\\Controllers\\';
        $controllerNamespace .= str_replace('/', '\\', $controller);
        $routeContent = File::get($this->getStub());
        $replace = [];
        $replace['{{ route }}'] = $route;
        $replace['{{ controller }}'] = class_basename($controller);

        $routeContent = str_replace(
            array_keys($replace),
            array_values($replace),
            $routeContent
        );
        $filePath = $this->getFilePath();

        $importStatement = "\nuse " . rtrim($controllerNamespace, '\\') . ";";
        if (!$this->controllerAlreadyImported($importStatement, $filePath)) {
            $this->prependToRoutesFile($importStatement, '<?php', $filePath);
        }


        if (!$this->routeAlreadyExists($routeContent, $filePath)) {
            File::append($filePath, $routeContent);
            $this->info("Route $route generated successfully!");
        } else {
            $this->error("Route $route already exists!");
        }
    }

    protected function getStub()
    {
        // Define the path to the published stubs in the application
        $publishedPath = resource_path('views/vendor/generate-resources/stubs/routes.stub');

        // Check if the custom published stub exists
        if (file_exists($publishedPath)) {
            return $publishedPath;
        }

        // Fall back to the package's default stub if no custom one is found
        return __DIR__ . '/../../stubs/routes.stub';
    }


    protected function prependToRoutesFile($content, $startPattern, $filePath)
    {
        $fileContent = File::get($filePath);
        $position = strpos($fileContent, $startPattern) + strlen($startPattern);
        $newContent = substr_replace($fileContent, $content, $position, 0);
        File::put($filePath, $newContent);
    }

    protected function controllerAlreadyImported($importStatement, $filePath)
    {
        $fileContent = File::get($filePath);
        return strpos($fileContent, $importStatement) !== false;
    }

    protected function routeAlreadyExists($routeContent, $filePath)
    {
        $fileContent = File::get($filePath);
        return strpos($fileContent, $routeContent) !== false;
    }

    protected function getFilePath()
    {
        $filepath = config('generate-resources.route_file_path', 'routes/web.php');
        return base_path($filepath);
    }
}
