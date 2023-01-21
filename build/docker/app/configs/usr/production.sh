#!/usr/bin/env sh

./bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration

/usr/bin/supervisord -n -c /etc/supervisord.conf
