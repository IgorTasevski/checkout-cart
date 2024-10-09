# Market/Store Application

This project is a web application built with PHP, JavaScript, and the Laravel framework. It was initiated as a basic Laravel project and has since had several new features implemented.

## Database Schema

The database for this project is structured to support the Product, Sku and Configurations (promotions) Repository feature.

The `products` table includes the following fields:

- `id`: (id)A unique identifier for each Product.
- `uuid`: (uuid) A universally unique identifier for each Product.
- `name`: (string) The original Product that was shortened.
- `price`: (integer - in pence) The base Product for the shortened Product.
- `created_by`: (FK -> Users constrained) Foreign key users->id.
- `updated_by`: (FK -> Users constrained) Foreign key users->id
- `created_at`: (timestamp) The date and time when the Product was created.
- `updated_at`: (timestamp) The date and time when the Product was last updated.
- `deleted_at`: (timestamp - soft delete) The date and time when the Product was deleted (if it was deleted).

Please refer to the Laravel migration files for the exact structure and data types of these fields.

## New Features

###  Repository Pattern

The repository pattern was used for building this. Meaning that the `Model`RepositoryInterface and `Model`Repository were created. The `Model`RepositoryInterface is an interface that defines the methods that the `Model`Repository must implement. The `Model`Repository is a class that implements the `Model`RepositoryInterface and contains the logic for interacting with the database.

The interface is in `App\Repositories\Contracts` and the repository is in `App\Repositories`.

The interface serves as a contract that the repository must adhere to. When instantiating the repository, we bind the interface to the repository in the `RepositoryServiceProvider`. In addition, we do private readonly on the Interface by following SOLID principles, namely the Interface Segregation Principle and the Dependency Inversion Principle.

Everything related to database operations is done in the repository, whereas the business logic is done in the service.

### Repository binding

In `bootstrap/app.php` the `App\Providers\RepositoryServiceProvider::class` was added thus allowing us to bind the `Model`RepositoryInterface with the `Model`Repository.

### Routes

The routes are in `api.php`.

### Traits

We are using traits for reusable code. The traits are in `App\Traits`. The trait used is hardcoded due to the nature of the project.

### Requests

We are using custom requests for validation. The requests are in `App\Http\Requests[Model]`.

### Data for testing

Please run `php artisan db:seed` to seed the database with some data for testing.

### Data fetching.

We are using Laravel Resource for data fetching. The resources are in `App\Http\Resources[Model]`.

### Postman

The postman collection is in the root directory of the project, which you can import for testing.

## Installation

1. Clone the repository
2. Run `composer install` to install the PHP dependencies
3. Set up your `.env` file with your database and other environment settings. Copy the `.env.example` file and rename it to `.env` - then fill in the necessary values with your own
4. Run `php artisan migrate` to create the database tables
5. Run `php artisan serve` to start the Laravel server
6. In a new terminal, navigate to the `url-shortener` directory and run `npm run dev` to start the Vite server for the frontend
7. Open your browser and navigate to `http://localhost:[phpport]` (or whatever port your Vite server is running on)
8. I used [Laravel Herd](https://herd.laravel.com/windows) for this project.
9. Run `php artisan db:seed` to seed the database with some data for testing.
10. You can now test the application using Postman. The collection is in the root directory of the project.
