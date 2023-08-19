<?php

namespace Asif160627\GenerateResources\Tests\Feature;

use Tests\TestCase;

class GenerateResourcesCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGenerateResourcesCommand()
    {
        $this->artisan('generate:resource', ['name' => 'DemoTest'])
            ->expectsOutput('Resource generated successfully.')
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Http/Controllers/DemoController.php'));
        $this->assertFileExists(app_path('Http/Resources/DemoResource.php'));
        $this->assertFileExists(app_path('Http/Requests/DemoRequest.php'));
        $this->assertFileExists(app_path('Models/Demo.php'));
        $this->assertFileExists(app_path('Services/DemoService.php'));
    }
}
