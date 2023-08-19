# Generate Resources Command

Generate Laravel Model, Migration, Service, Controller, Resources with a single command.

## Installation

You can install this package via Composer:

```bash
composer require asif160627/generate-resources

```

## Usage
After installing the package, you can use the generate:resource command to generate the necessary files for a resource:

```bash
php artisan generate:resource {name}

```

Replace {name} with the desired name of your resource. This command will generate a model, migration, service, controller, resource, and other related files.

Replace {name} with the desired name for your resource. The command will create the following files associated with the specified resource:

A model with the specified name.
A migration file for the model's database table.
A service class for managing the resource's business logic.
A controller for handling HTTP requests and responses.
A resource class for formatting the resource's data in API responses.
A request class for validating input data.

## Testing
To run the package tests, execute the following command:

```bash
php artisan test --filter GenerateResourcesCommandTest

```

This will run the tests for the generate:resource command and ensure its functionality.

## Contributing
If you encounter issues or have suggestions, feel free to create an issue or pull request on GitHub.

## License
This package is open-source software licensed under the MIT License.



