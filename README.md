# Phone Book (API)

## Documentation

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
```
$ composer install
$ cp .env.example .env
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
$ symfony server start
```

## Running Test

```shell script
$ php bin/console --env=test doctrine:database:create
$ php bin/console --env=test doctrine:schema:create
$ php bin/phpunit

```

---

## Api Documentation

---
There are four routes included on this project to be used.

- Method: GET `http://127.0.0.1:8000/contacts`.

---

- Method: POST `http://127.0.0.1:8000/contact/store`.

- Body:
```
    {
        "first_name": "Lekan",
        "last_name": "Lekan",
        "address": "Yo, 22 Adkdjd",
        "phone_number": "1039398383",
        "birthday": "10/2/2021",
        "email": "yo@mail.com"
    }
```

---

- Method: PUT `http://127.0.0.1:8000/contact/edit/1`.

- Response:

---


- Method: DELETE `http://127.0.0.1:8000/contact/delete/1`.

- Response:

---