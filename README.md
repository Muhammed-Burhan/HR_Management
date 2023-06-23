# HR Management API

This is an HR management API that provides endpoints for managing warehouses, branches, and devices in each branch.

## Status

The initial setup of the HR management API is complete. The following endpoints are now available:

- All the required tables have been created.
- All the required models and controllers have been created.
- Authentication setup has been finished.
- Endpoints for warehouse management have been created.
- Exception handling has been implemented and certain errors have been fixed.
- Required relationships between the tables have been established.
- Endpoints for branch model have been completed.
- The method to retrieve all branches of the same warehouse has been implemented.
- The method to retrieve all devices related to the same warehouse has been completed.
- User actions are now logged.
- Export and import functionalities have been added.
- JSON export of the device table has been implemented.
- Seeds files added to populate the database.
- Caching and pagination have been implemented.
- Scheduled database backup has been added.

## Installation

To install this application, follow these steps:

1. Clone the repository:

```
   git clone https://github.com/Muhammed-Burhan/HR_Management.git
```

2. Change branch to development:

```             
   git checkout development
```

3. Install the dependencies:

```
   composer install
```

4. Copy the `.env.example` file to `.env` (create `.env` file):

5. Update the `.env` file with the appropriate database information and other settings.

6. Run the database migrations:

```
   php artisan migrate
```

7. To test all features, populate the database and create a Super_Admin. Use the following command to accomplish this:
   (it will take some time, because we will create 3000 devices)
```
   php artisan db:seed
```

The command populates the database and sets up a super_admin account. Here are the login credentials:

```
   Email: test_user@test.com
   Password: 5451129
```

7. Start the local development server:

```
   php artisan serve
```

8. To automatically export and back up the database daily, run the following command(you can find the file inside :storage/app):

```
   php artisan db:backup
```

> [Note]:To automatically back up the database daily,you have to run the following command:

```
   php artisan schedule:run
```

9. To export devices table to json, you can run the following command(you can find the file inside: storage/app):

```
  php artisan devices:export-json
```

## Contact

If you have any questions or feedback, please contact me at mohammed_burhan@outlook.com
