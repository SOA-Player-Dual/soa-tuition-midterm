# Tuition Service

This is the service for Tuition banking web application.

- PHP 8.0.2
- Larravel Framework 9.34.0

## Installation

Here is how you can run the project locally:

- Run the following command to copy ".env.example file" to ".env" file

```bash
cp .env.example .env
```

- Run the following command

```bash
php artisan key:generate
```

- Configure the following environment variables to your ".env" file

```bash
DB_CONNECTION=mysql
DB_HOST=18.143.47.233
DB_PORT=3306
DB_DATABASE=soa_tuition
DB_USERNAME=admin
DB_PASSWORD=adminpassword
```

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=khang.dacntt2@gmail.com
MAIL_PASSWORD=nhqdhnankwgofqdy
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=khang.dacntt2@gmail.com
MAIL_FROM_NAME='Tuition OTP'
```

- Install PHP dependencies:

```bash
composer install
```

- Run server: You can use xampp or run the following command.
Note: If you use xampp to run this project, please run by command for soa-authenticate-service project and vice versa

```bash
php artisan serve
```