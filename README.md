# HR Management API

This is an HR management API that provides endpoints for managing warehouses, branches, and devices in each branch.

## Status

The initial setup of the HR management API is complete. The following endpoints are now available:

- All the required tables created.
- All the required models and controllers created.
- Finished setting up the auth.
- Created the endpoints for warehouse.
- Added Exception Handling and fixed some errors.
- SetUp required relationships between the tables.
- Finished the necessary endpoints for the branch model.
- Finished the method which returns all branches of the same warehouse.
- Finished the method which returns all devices related to the same warehouse.
- User actions are now logged.
- Export and import functionality added.
- JSON export of the device table added.

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

7. To export the database (you can find the export file inside `storage/app`)

```
   php artisan database:export
```

8. To export the devices table to JSON (you can find the export file inside `storage/app`):

```
  php artisan devices:export-json
```


**Note:**  
- For using the email service, you have to register in [Mailtrap](https://mailtrap.io/) and provide the following info inside the `.env` file:
  ```
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=PortNumber
    MAIL_USERNAME=USERNAME
    MAIL_PASSWORD=Password
    MAIL_ENCRYPTION=tls
```
-Please be aware that you need to create an account and use a token in the header to request the routes.


## Contact

If you have any questions or feedback, please contact me at mohammed_burhan@outlook.com.
