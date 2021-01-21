composer install
APP_ENV=prod php bin/console doctrine:migrations:migrate --no-interaction
APP_ENV=prod php bin/console messenger:setup-transports
chmod -R 777 vendor
chmod -R 777 upload
chmod -R 777 public/images
chmod -R 777 var/data.db
