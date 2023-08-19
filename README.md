# Laravel Generate Resources

Generate Laravel Model, Migration, Service, Controller, Resources with a single command.

## Installation

You can install this package via Composer:

```bash
composer require asif160627/laravel-generate-resources

```

## Usage
After installing the package, you can use the generate:resource command to generate the necessary files for a resource:

```bash
php artisan generate:resource {name}

```

Replace {name} with the desired name of your resource. This command will generate a model, migration, service, controller, resource, and other related files.

Replace {name} with the desired name for your resource. The command will create the following files associated with the specified resource:

The `generate:resource` command creates the following files for a new resource:

- A model with the specified name.
- A migration file for the model's database table.
- A service class for managing the resource's business logic.
- A controller for handling HTTP requests and responses.
- A resource class for formatting the resource's data in API responses.
- A request class for validating input data.


## Publishing Resources
This package supports resource publishing, allowing you to customize the generated files to fit your project's needs. To publish the package's resources, you can use the following command:

```bash
php artisan vendor:publish --provider="Asif160627\GenerateResources\ResourceServiceProvider"
```
This command will list the available resources for publishing. You can choose which resources you want to publish by selecting the associated numbers.

## Example
For instance, if you want to generate the necessary files for a resource named "Product," you would execute the following command:

```bash
php artisan generate:resource Product

```


## Subfolders
If you want to organize the generated files within a specific subfolder, you can specify the subfolder along with the resource name. For example, to create a Product resource within an Admin subfolder, use the following command:

```bash
php artisan generate:resource Admin/Product

```

The command will create the necessary files for the Product resource within the Admin subfolder.

## Customizing Generated Files
The generate:resource command streamlines the process of generating common resource files. However, you can always modify and extend these files further to match your project's specific requirements.

## Testing
To run the package tests, execute the following command:

```bash
php artisan test --filter GenerateResourcesCommandTest

```

This will run the tests for the generate:resource command and ensure its functionality.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).



