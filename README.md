## Phone Book (API)
Create an application based on phone book. The application should have the following

- Add other customers as contact.
- Edit created contacts.
- Delete existing contacts
- Search for contacts by name

Each contact will need the following information:
- First name
- Last name
- Address information
- Phone number
- Birthday
- email address
- Picture (optional)

## Technologies

- PHP 8.0
- Symfony
- Mysql

## How to run the application
Below are the steps you need to successfully set up and run the application.

- Clone the app from the repository and cd into the root directory of the app
- Run composer install
- Copy .env.example into .env
- Run php bin/console doctrine:database:create
- Run php bin/console make:migration
- Run php bin/console doctrine:migrations:migrate
- Run symfony server start

## Running Test
> php bin/phpunit
