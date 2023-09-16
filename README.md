# Econumo API-backend

## How to use self-hosted version

1. Copy `docker-compose.example.yml` to your server
2. Replace `<FIXME>` with your variables
3. Run `docker-composer up -d`
4. Open in web-browser url `http://localhost:8080` and check frag "self-hosted" with value `http://localhost:8082` (it's API url)

### Currencies 

#### How to load currencies

Please use the currency loader to fulfill your database: https://github.com/econumo/currency-loader

Alternatively, you can use the API call: 
```bash
curl -X 'POST' \
  '<YOUR_API_BASE_URL>/api/v1/system/import-currency-list' \
  -H 'accept: application/json' \
  -H '<ECONUMO_SYSTEM_API_KEY>' \
  -H 'Content-Type: application/json' \
  -d '{
  "items": [
    "USD",
    "EUR",
  ]
}'
```

If you want to change base currency (by default USD) - update the value of `CURRENCY_BASE` in the `.env`. 