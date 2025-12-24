.PHONY: up down init artisan reset logs help

# Default target
help:
	@echo "TaskFlow - Available Make Targets:"
	@echo "  make up      - Start containers"
	@echo "  make down    - Stop containers"
	@echo "  make init    - Install dependencies, copy .env, start Sail, run migrations & seeders"
	@echo "  make artisan - Run Artisan commands (usage: make artisan cmd='migrate')"
	@echo "  make reset   - Rebuild and reinitialize a clean environment"
	@echo "  make logs    - View Laravel logs (tail)"

# Start containers
up:
	./vendor/bin/sail up -d

# Stop containers
down:
	./vendor/bin/sail down

# Initialize the project
init:
	@echo "Installing Composer dependencies..."
	composer install
	@echo "Copying .env file..."
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@echo "Starting Sail containers..."
	./vendor/bin/sail up -d
	@echo "Generating application key..."
	./vendor/bin/sail artisan key:generate
	@echo "Running migrations..."
	./vendor/bin/sail artisan migrate
	@echo "Running seeders..."
	./vendor/bin/sail artisan db:seed
	@echo "Initialization complete!"

# Run Artisan commands
# Usage: make artisan cmd='migrate:status'
artisan:
	./vendor/bin/sail artisan $(cmd)

# Reset environment - rebuild and reinitialize
reset:
	@echo "Stopping containers..."
	./vendor/bin/sail down -v
	@echo "Rebuilding containers..."
	./vendor/bin/sail build --no-cache
	@echo "Starting containers..."
	./vendor/bin/sail up -d
	@echo "Running fresh migrations with seeders..."
	./vendor/bin/sail artisan migrate:fresh --seed
	@echo "Reset complete!"

# View Laravel logs
logs:
	tail -f storage/logs/laravel.log
