
cd /var/www/
git pull
composer install
php artisan migrate

cd /var/www/frontend
gulp less
gulp js
