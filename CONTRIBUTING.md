# How to contribute

## Run application

1. (Recommended) Install [task runner v3](https://taskfile.dev/#/installation) for using shortcuts from [Taskfile.yml](Taskfile.yml) 
2. Clone repository
3. Run `task up`
4. To full rebuild application use `task restart` (it rebuilds docker images, composer, etc)


## Run tests

Run `task test` for all tests. Or you could use arguments for Codeception: `task test -- unit`


## Api documentation: Swagger

To access Swagger doc - follow the url `/api/doc`. For generating token use
console command: `bin/console lexik:jwt:generate-token john@econumo.test`


## Create new API-method

For now only `GET-` and `POST-` methods are allowed. `GET` is used for reading, `POST` for writing.

See command `./bin/console make:api --help` for create new method.

