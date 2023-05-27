# Solar App

## Installation
- Install PHP and Composer
- Execute this to create .env file `php -r "file_exists('.env') || copy('.env.example', '.env');"`
- Create your database and update .env file information's
- Migrate data with the following command `php artisan migrate --seed`
- Or you can just migrate first with `php artisan migrate` and populate data with `php artisan db:seed`
- 


## Execution

To execute the project, you will just have to enter the next command: `php artisan serve`

And open the link [http://localhost:8000](http://localhost:8000)


## Credits

Alvine Simo
