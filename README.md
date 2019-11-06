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

## Run server
After all steps, you can start the server that will run o port 8000, then please open your browser by URI address
```
http://localhost:8000/test
```
* I created TestController.php as our main environment system


# Additional Info

## structure System

All working files stored in these directories:

```
Root
    - config
        - package
            - fos_rest.yml  # to define format listener of rout path
        - routes.yml # define route alias of controller (in several case I define route in controller to simulate annotation route
        - services.yml # I defined some parameter there to make it globally and able to be called anywhere
        
   - public
        - files # It will be used to store exported csv and jsonl files
        - source # I use this directory to simulate load data from local
        
   - src 
        - Controller # it is collection of all controller files 
        - Entity # it is collection of entity definition that will be model to get and setter variable
        - Form # it user to validate form value according to entity definition    
        - Migration # it is generate sql when we run migration process based on entiity definition. we can put additional query that will be run while migration process running.
        - Repository # it generated automatically when we create entity
        
   - templates # it is twig teplate collection.
   
- .env # main configuration of environment system. we can define in this variable to make it globallly
    
    
        

```
