# HR Management API

This is an HR management API that provides endpoints for managing warehouse, branches, and devices in each branch.

## Status

The initial setup of the HR management API is complete. The following endpoints are now available:

-   All the required tables created.
-   All the required model and controllers created.
-   Finished setting up the auth.
-   Created the end points for warehouse.
-   Added Exception Handling and fixed some errors.
-   SetUp required relationships between the tables.
-   Finished the need endpoints for branch model.
-   Finished the method which return all branches of the same warehouse.
-   Finished the method which returns all devices related to the same warehouse.
-   now all user actions are logged.
-   export and import added
-   Json export of the device table added

## Installation

To install this application, follow these steps:

1. Clone the repository:

```
   git clone https://github.com/Muhammed-Burhan/HR_Management.git
```

2. Install the dependencies:

```
   composer install
```

3. Copy the `.env.example` file to `.env`:

4. Update the `.env` file with the appropriate database information and other settings.

5. Run the database migrations:

```
   php artisan migrate
```

6. Start the local development server:

```
   php artisan serve
```

7. To export database (you can find the file inside :storage/app):

```
   php artisan database:export
```

8. To export devices table to json (you can find the file inside: storage/app):

```
  php artisan devices:export-json

```

## Contact

If you have any questions or feedback, please contact me at mohammed_burhan@outlook.com
