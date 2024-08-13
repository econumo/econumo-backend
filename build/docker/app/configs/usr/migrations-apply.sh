#!/usr/bin/env sh

cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration
