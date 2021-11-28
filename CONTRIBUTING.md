# How to contribute

## Run application

1. (Recommended) Install [task runner v3](https://taskfile.dev/#/installation) for using shortcuts from [Taskfile.yml](Taskfile.yml) 
2. Clone repository
3. Run `task up`

## Run tests

Run `task test` for all tests. Or you could use arguments for Codeception: `task test -- unit`

## Api documentation: Swagger

To access Swagger doc - follow the url `/api/doc`. For generating token use
console command: `bin/console lexik:jwt:generate-token john@snow.test`


## Create new API-method

For now only `GET-` and `POST-` methods are allowed. `GET` is used for reading, `POST` for writing.

See command `./bin/console make:api --help` for create new method.



## Secrets

- `DEPLOY_HOST`: docker-хост для доступа по ssh
- `DEPLOY_USERNAME`: имя пользователя
- `DEPLOY_KEY`: приватный ключ


## Тех. долг
- Перенести APP_SECRET в секреты
- Завязать на APP_SECRET формирование jwt-ключа
- В продакшене использовать другие jwt-ключи