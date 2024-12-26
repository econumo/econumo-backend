<p align="center">
    <picture>
        <img src="https://github.com/econumo/.github/raw/master/profile/econumo.png" width="194">
    </picture>
</p>

<p align="center">
    A getting started guide to self-hosting <a href="https://econumo.com/docs/" target="_blank">Econumo CE</a>
</p>

---

## Description

This is the main backend repository for Econumo, containing the backend for [Econumo CE](https://econumo.com/docs/edition/).
The backend is built using PHP, Symfony, and SQLite.

The "main" branch may not be stable. Use tags for stable versions.

## Contribution

Run the Application

1. (Optional) Install [task runner v3](https://taskfile.dev/#/installation) for using shortcuts from [Taskfile.yml](Taskfile.yml)
2. Clone the repository.
3. Create a `.env.local` to override the default `.env`
4. Run `task up`

### Run tests

Run `task test` to execute all tests, or use arguments for Codeception: `task test -- unit`.

### Api documentation: Swagger

To access the Swagger documentation, follow the URL `/api/doc`. To generate a token, use the console command: `bin/console lexik:jwt:generate-token john@econumo.test`.


## Documentation

For more information on installation, upgrades, configuration, and integrations please see our [documentation.](https://econumo.com/docs/)

## Contact

- For a question or advice please use [GitHub discussions](https://github.com/orgs/econumo/discussions)
