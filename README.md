# Employee Management API

This is a simple API built with Laravel for managing employee data. It uses Laravel Sail for easy Docker container management.

## Requirements

- Docker
- Composer

## Setup Instructions

1. **Clone the repository**

    ```
    git clone <repository-url>
    ```

2. **Navigate to the project directory**

    ```
    cd employee-app
    ```

3. **Start Laravel Sail**

    Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment. Start all of the Docker containers for the application with:

    ```
    ./vendor/bin/sail up
    ```

    If you would like to start the containers in the background, you may start Sail in "detached" mode:

    ```
    ./vendor/bin/sail up -d
    ```

4. **Install dependencies**

    While Sail is running, you may execute commands as you would typically run them on your own machine. However, prefixed by `sail`. For instance, you may use the following command to install the application's Composer dependencies:

    ```
    ./vendor/bin/sail composer install
    ```

5. **Setup Environment Variables**

    Copy the example env file and make the required configuration changes in the `.env` file:

    ```
    cp .env.example .env
    ```

6. **Generate Application Key**

    Laravel requires you to have an app encryption key which is generally randomly generated and stored in your `.env` file. Application key can be generated with the command:

    ```
    ./vendor/bin/sail artisan key:generate
    ```

7. **Run Migrations**

    Run the database migrations. Laravel's migration system provides a variety of commands for your databases:

    ```
    ./vendor/bin/sail artisan migrate
    ```

8. **Seed the database**

    You may need to seed your database with test data or create a first user. You can use the command below:

    ```
    ./vendor/bin/sail artisan db:seed
    ```

## API Endpoints

- GET `/api/employee`: Fetch all employees.
- POST `/api/employee`: Import employees from a CSV file.
- GET `/api/employee/{id}`: Fetch a specific employee by Employee ID.
- DELETE `/api/employee/{id}`: Delete a specific employee by ID.

## Testing

Explain how to run the automated tests for this system.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).