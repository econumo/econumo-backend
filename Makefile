.PHONY: up restart rebuild down sh root run fast-test test-failed test composer-install help

# Default target - show help
help:
	@echo "Available targets:"
	@echo "  make up           - Run application"
	@echo "  make restart      - Restart application"
	@echo "  make rebuild      - Rebuild application"
	@echo "  make down         - Stop application"
	@echo "  make sh           - Jump into application container"
	@echo "  make root         - Jump into application container as ROOT"
	@echo "  make run ARGS='...' - Run symfony command"
	@echo "  make fast-test ARGS='...' - Run tests without recreating db"
	@echo "  make test-failed ARGS='...' - Run only failed tests"
	@echo "  make test ARGS='...' - Run tests"
	@echo "  make composer-install - Install composer dependencies"

# Run application
up: composer-install
	docker-compose up -d
	docker-compose exec -uwww-data app bin/console doctrine:migrations:migrate -n

# Restart application
restart: down up

# Rebuild application
rebuild:
	docker-compose up -d --build --remove-orphans
	docker-compose exec -uwww-data app composer install
	docker-compose exec -uwww-data app bin/console doctrine:migrations:migrate -n

# Stop application
down:
	docker-compose down --remove-orphans

# Jump into application container
sh:
	docker-compose exec -uwww-data app sh

# Jump into application container as ROOT
root:
	docker-compose exec app sh

# Run symfony command
# Usage: make run ARGS='doctrine:migrations:status'
run:
	docker-compose exec -uwww-data app bin/console $(ARGS)

# Run tests without recreating db
# Usage: make fast-test ARGS='unit'
fast-test:
	docker-compose up -d
	-docker-compose exec -uwww-data app vendor/bin/codecept run $(ARGS) --steps -v

# Run only failed tests
# Usage: make test-failed ARGS='unit'
test-failed:
	docker-compose up -d
	-docker-compose exec -uwww-data app vendor/bin/codecept run $(ARGS) --steps -v -g failed

# Run tests
# Usage: make test ARGS='unit'
test:
	docker-compose up -d
	-docker-compose exec -uwww-data app bin/console doctrine:database:drop --force --env=test -vvv
	docker-compose exec -uwww-data app bin/console doctrine:database:create --env=test -vvv
	docker-compose exec -uwww-data app bin/console doctrine:migration:migrate -n --env=test -vvv
	docker-compose exec -uwww-data app bin/console doctrine:fixtures:load --purge-with-truncate -n --env=test -vvv
	-docker-compose exec -uwww-data app vendor/bin/codecept run $(ARGS) --steps -v

# Install composer dependencies
composer-install:
	@if [ ! -d vendor ]; then \
		docker-compose exec -uwww-data app composer install; \
	fi
