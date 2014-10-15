
cd /var/www/
git pull
composer install
php artisan migrate
php artisan compile:templates

cd /var/www/frontend
gulp less
gulp js
