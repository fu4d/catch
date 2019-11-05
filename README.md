# CATCH - order data processor

## Some necessary dependencies 
```
composer require symfony/finder
composer require symfony/orm-pack
composer require friendsofsymfony/rest-bundle
composer require jms/serializer-bundle
composer require symfony/validator
composer require symfony/form
```

## Getting Started

After clone this repository, you need to install all dependencies by running these code
```
composer install
```

### Database preparation

We need to edit .env file to configure database setting. we need to edit this line code
```
    DATABASE_URL="mysql://DB_USER:DB_PASSWORD@localhost:3306/DB_NAME"
```

After that, please open your terminal and then create database by run this code in your root project directory
```
    php bin/console doctrine:database:create
```

### Generate table and insert data
Please run this code to generate table and data 

```
php bin/console doctrine:migrations:migrate
```
