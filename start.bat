%systemDrive%\xampp\mysql\bin\mysql -uroot -e

php -r "copy('.env.example', '.env');"

call composer install

call composer require stripe/stripe-php

call php artisan key:generate

call php artisan storage:link

code .
