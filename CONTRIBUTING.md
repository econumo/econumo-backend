# How to contribute

## Run application

1. (Recommended) Install [task runner v3](https://taskfile.dev/#/installation) for using shortcuts from [Taskfile.yml](Taskfile.yml) 
2. Clone repository
3. Run `task up`

## Run tests

Run `task test` for all tests. Or you could use arguments for Codeception: `task test -- unit`


## Secrets

- `DEPLOY_HOST`: docker-хост для доступа по ssh
- `DEPLOY_USERNAME`: имя пользователя
- `DEPLOY_KEY`: приватный ключ


## Тех. долг
- Перенести APP_SECRET в секреты
- Завязать на APP_SECRET формирование jwt-ключа
- В продакшене использовать другие jwt-ключи