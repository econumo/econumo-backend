#!/usr/bin/env sh

cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration
echo "* * * * * www-data  cd /var/www && php bin/console schedule:run >> /dev/null 2>&1" | crontab -u www-data -

if [[ -n "${NEW_RELIC_API_KEY}" ]]; then
  mkdir -p /opt/newrelic && \
      cd /opt/newrelic && \
      wget -qO- https://download.newrelic.com/php_agent/archive/10.7.0.319/newrelic-php5-10.7.0.319-linux.tar.gz | tar xvz -C /opt/newrelic && \
      cd /opt/newrelic/newrelic-php5-10.7.0.319-linux/ && \
      export NR_INSTALL_USE_CP_NOT_LN=1 && \
      export NR_INSTALL_SILENT=1 && \
      source newrelic-install install && \
      rm -rf /opt/newrelic
fi

/usr/bin/supervisord -n -c /etc/supervisord.conf
