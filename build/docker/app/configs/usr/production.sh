#!/usr/bin/env sh

cd /var/www && php bin/console doctrine:migrations:migrate --quiet --no-interaction --allow-no-migration

if [[ -n "${NEW_RELIC_API_KEY}" ]]; then
  curl -L https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz | tar -C /tmp -zx \
      && export NR_INSTALL_USE_CP_NOT_LN=1 \
      && export NR_INSTALL_SILENT=1 \
      && /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install \
      && rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

  find /etc /opt /usr/local/etc -type f -name newrelic.ini \
      -exec sed -i \
          -e "s/REPLACE_WITH_REAL_KEY/${NEW_RELIC_LICENSE_KEY}/" \
          -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname = \"${NEW_RELIC_APP_NAME}\"/" \
          -e "s/;newrelic.daemon.address[[:space:]]=[[:space:]].*/newrelic.daemon.address = \"${NEW_RELIC_AGENT_HOST}:31339\"/" {} \;
fi

/usr/bin/supervisord -n -c /etc/supervisord.conf
