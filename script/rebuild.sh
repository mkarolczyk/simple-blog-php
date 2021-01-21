composer install

APP_ENV=dev php bin/console messenger:setup-transports
APP_ENV=dev php bin/console doctrine:schema:drop --force

APP_ENV=dev php bin/console cache:clear
APP_ENV=dev php bin/console doctrine:cache:clear-query --flush
APP_ENV=dev php bin/console doctrine:cache:clear-result --flush
APP_ENV=dev php bin/console doctrine:cache:clear-metadata --flush

APP_ENV=dev php bin/console doctrine:schema:update --force
APP_ENV=dev php bin/console doctrine:fixtures:load --no-interaction
