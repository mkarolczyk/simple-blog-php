composer install
APP_ENV=prod php bin/console doctrine:migrations:migrate --no-interaction
APP_ENV=prod php bin/console messenger:setup-transports
chmod -R 777 /var/www/html/upload
chmod -R 777 /var/www/html/public/images
chmod -R 777 /var/www/html/var/data.db
