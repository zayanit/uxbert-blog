## Installation Instractions


### 1. Clone the project
`` git clone https://github.com/zayanit/uxbert-blog.git ``
if you want to use another name add the name you want at the end of the last command

### 2. Create .env file 
Copy ``.env.example`` file content and past it in a new file called ``.env``

### 3. Generate app key
Running ``php artisan key:generate`` in the project. You will get the following output: ``Application key set successfully.`` and you will see like like this ``APP_KEY=base64:O/+B7gtDnXAY8CcmUQY8eXiCcdSeXSx80j5jLKC9Jw8=`` in ``.env`` file

### 4. Database connection
You have to create a new database and edit these lines to set it to your connection
````
B_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
````

### 5. Installing Dependencies
To do that you need to run ``composer install``

### 6. Run migrations
Now you need to run migrations to create database tables then run seed

````
php artisan migrate
````

### 7. Host & Run
You can host the application on a local server like `MAMP` or `XAMPP` or you can run this command 
```
php artisan serve
```

### Congrats you've finished
