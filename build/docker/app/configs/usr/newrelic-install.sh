#!/usr/bin/env sh

if [[ -n "${NEW_RELIC_API_KEY}" ]]; then
  echo "Downloading New Relic PHP agent: https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz"
  curl -L https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz | tar -C /tmp -zx

  echo "Installing New Relic PHP agent /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install"
  ls -la /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install
  export NR_INSTALL_USE_CP_NOT_LN=1 \
      && export NR_INSTALL_SILENT=1 \
      && /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install
  echo "Removing New Relic PHP agent installation files"
  rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

  echo "Configuring New Relic PHP agent"
  find /etc /opt /usr/local/etc -type f -name newrelic.ini
  find /etc /opt /usr/local/etc -type f -name newrelic.ini \
      -exec sed -i \
          -e "s/REPLACE_WITH_REAL_KEY/${NEW_RELIC_LICENSE_KEY}/" \
          -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname = \"${NEW_RELIC_APP_NAME}\"/" \
          -e "s/;newrelic.daemon.address[[:space:]]=[[:space:]].*/newrelic.daemon.address = \"${NEW_RELIC_AGENT_HOST}:${NEW_RELIC_AGENT_PORT}\"/" {} \;
fi
