# Laravel Assessment

> Laravel 8.40 API This is an API for an auth CRUD app

## Quick Start

``` bash
# Install Dependencies
composer install

# Run Migrations
php artisan migrate

# Add virtual host if using Apache

# If you get an error about an encryption key
php artisan key:generate
```

## Endpoints

### To send email invitation for signup
``` bash
POST api/send_invitation
email/
```
### To register user on the site
``` bash
POST api/register
email/user_name/password
```

### Verify account by adding pin from email
``` bash
POST api/verify
email/pin
```

### Login user and return user ID
``` bash
POST api/login
email/password
```

### Update user
``` bash
PUT api/update
id/password/avatar
```


```

## App Info

### Author

Adnan Asad

### Version

1.0.0

### License

This project is licensed under the MIT License
