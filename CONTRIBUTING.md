## Secrets

- `DEPLOY_HOST`: docker-хост для доступа по ssh
- `DEPLOY_USERNAME`: имя пользователя
- `DEPLOY_KEY`: приватный ключ


## Тех. долг
- Перенести APP_SECRET в секреты
- Завязать на APP_SECRET формирование jwt-ключа
- В продакшене использовать другие jwt-ключи