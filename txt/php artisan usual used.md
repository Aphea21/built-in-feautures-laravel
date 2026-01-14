php artisan migrate:fresh --seed

php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProductSeeder 
php artisan db:seed --class=CustomerSeeder 
php artisan db:seed --class=CategorySeeder 
php artisan db:seed --class=OrderSeeder 

php artisan icons:cache     