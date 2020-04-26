FROM rodolpheche/wiremock

EXPOSE 80

CMD java  -cp /var/wiremock/lib/*:/var/wiremock/extensions/* com.github.tomakehurst.wiremock.standalone.WireMockServerRunner --port 80
