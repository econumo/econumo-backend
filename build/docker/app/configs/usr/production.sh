#!/usr/bin/env sh

cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration

if [[ -n "${NEW_RELIC_API_KEY}" ]]; then
  wget -qO- https://download.newrelic.com/php_agent/archive/10.7.0.319/newrelic-php5-10.7.0.319-linux.tar.gz | tar xvz -C /tmp && \
      cd /tmp/newrelic-php5-*/ && \
      export NR_INSTALL_USE_CP_NOT_LN=1 && \
      export NR_INSTALL_SILENT=1 && \
      sh newrelic-install install -n logs-integration && \
      rm -rf /tmp/newrelic-php5-* /tmp/nrinstall* && \
      sed -i \
        -e "s/\"REPLACE_WITH_REAL_KEY\"/\"$NEW_RELIC_LICENSE_KEY\"/" \
        -e "s/\"PHP Application\"/\"$NEW_RELIC_APP_NAME\"/" \
        -e 's/;newrelic.daemon.app_connect_timeout =.*/newrelic.daemon.app_connect_timeout=15s/' \
        -e 's/;newrelic.daemon.start_timeout =.*/newrelic.daemon.start_timeout=5s/' \
        /usr/local/etc/php/conf.d/newrelic.ini
fi

/usr/bin/supervisord -n -c /etc/supervisord.conf
