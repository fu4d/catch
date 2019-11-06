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
Make sure this system running under PHP 7.1 +

## Getting Started

After clone this repository, you need to install all dependencies by running these code
```
composer install
```
## AWS Connection Preparation
Please make sure you have AWS credential file under /home/%USER%/.aws/credential
it should be contain like this code
```
[default]
aws_access_key_id = AWS_KEY
aws_secret_access_key = AWS_SECRET
```

## Email SMTP Service configuration
in .env file, find this line code and change with your gmail account and password
```
MAILER_URL=gmail://account@gmail.com:P4S5w0Rd@localhost?encryption=tls
```

## Database preparation

We need to edit .env file to configure database setting. we need to edit this line code
```
    DATABASE_URL="mysql://DB_USER:DB_PASSWORD@localhost:3306/DB_NAME"
```

## Instalation
### Create Database
After that, please open your terminal and then create database by run this code in your root project directory
```
    php bin/console doctrine:database:create
```

### Generate table and insert data
Please run this code to generate table and data 

```
php bin/console doctrine:migrations:migrate
```


