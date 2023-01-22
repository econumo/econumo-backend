#!/usr/bin/env sh

cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration
echo "* * * * * www-data  cd /var/www && php bin/console schedule:run >> /dev/null 2>&1" | crontab -u www-data -

/usr/bin/supervisord -n -c /etc/supervisord.conf
