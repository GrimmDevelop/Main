
cd /var/www/
/usr/bin/git pull
composer install
php artisan migrate
php artisan compile:templates

cd /var/www/frontend
bower update
gulp less
gulp js
