# Laravel Application Makefile

.PHONY: help install build dev setup run start stop kill restart logs test migrate seed fresh deploy clean perms

# Default target
help:
	@echo "Available commands:"
	@echo "  install     - Install dependencies and setup application"
	@echo "  build       - Build frontend assets"
	@echo "  dev         - Run npm dev server (watch mode)"
	@echo "  setup       - Complete setup (install + migrate)"
	@echo "  run         - Complete setup and run application (install + start + migrate)"
	@echo "  start       - Start Docker containers"
	@echo "  stop        - Stop Docker containers"
	@echo "  kill        - Force stop and remove all containers"
	@echo "  restart     - Restart Docker containers"
	@echo "  logs        - Show application logs"
	@echo "  test        - Run tests"
	@echo "  migrate     - Run database migrations"
	@echo "  seed        - Seed database with sample data"
	@echo "  fresh       - Fresh install (migrate + seed)"
	@echo "  deploy      - Production deployment"
	@echo "  clean       - Clean up containers and cache"
	@echo "  perms       - Fix file permissions"

# Install dependencies and setup
install:
	@echo "📦 Installing dependencies..."
	docker compose build app
	docker compose run --rm app composer install --no-dev --optimize-autoloader
	docker compose run --rm app php artisan key:generate
	docker compose run --rm app php artisan storage:link
	@echo "� Setting up file permissions..."
	docker compose run --rm app chown -R www-data:www-data storage bootstrap/cache
	docker compose run --rm app chmod -R 775 storage bootstrap/cache
	@echo "�� Installing npm dependencies..."
	cd src && npm install
	@echo "🔨 Building frontend assets..."
	cd src && npm run build
	@echo "✅ Installation complete!"

# Build frontend assets
build:
	@echo "🔨 Building frontend assets..."
	cd src && npm run build
	@echo "✅ Assets built!"

# Run npm dev server (watch mode)
dev:
	@echo "🔥 Starting npm dev server..."
	cd src && npm run dev

# Fix file permissions
perms:
	@echo "📁 Fixing file permissions..."
	docker compose run --rm app chown -R www-data:www-data storage bootstrap/cache
	docker compose run --rm app chmod -R 775 storage bootstrap/cache
	@echo "✅ Permissions fixed!"

# Complete setup (install + migrate)
setup: install migrate
	@echo "🚀 Setup complete! Application is ready."
	@echo "🌐 Application: http://localhost:8021"

# Start Docker containers
start:
	@echo "🚀 Starting containers..."
	docker compose up -d
	@echo "✅ Containers started!"
	@echo "🌐 Application: http://localhost:8021"

# Run application (complete setup + start)
run: install
	@echo "🚀 Starting containers..."
	docker compose up -d
	@echo "✅ Containers started!"
	@echo "🌐 Application: http://localhost:8021"
	@echo "⏳ Waiting for database to be ready..."
	@sleep 10
	@echo "🔍 Checking database connection..."
	@docker compose exec -T db mysqladmin ping -h localhost --silent || (echo "❌ Database not ready, waiting more..." && sleep 5 && docker compose exec -T db mysqladmin ping -h localhost --silent)
	@$(MAKE) migrate
	@echo "🚀 Application is fully ready!"
	@echo "🌐 http://localhost:8021"
	@echo "🔥 For frontend dev: make dev"

# Stop Docker containers
stop:
	@echo "🛑 Stopping containers..."
	docker compose down
	@echo "✅ Containers stopped!"

# Force stop and remove all containers
kill:
	@echo "💀 Force killing all containers..."
	docker compose down --remove-orphans --volumes --timeout 0
	docker compose kill || true
	docker system prune -f
	@echo "✅ All containers killed and removed!"

# Restart Docker containers
restart: stop start

# Show application logs
logs:
	docker compose logs -f app

# Run tests
test:
	@echo "🧪 Running tests..."
	docker compose run --rm app php artisan test

# Run database migrations
migrate:
	@echo "🗄️ Running migrations..."
	docker compose run --rm app php artisan migrate

# Seed database with sample data
seed:
	@echo "🌱 Seeding database..."
	docker compose run --rm app php artisan db:seed

# Fresh install (migrate + seed)
fresh:
	@echo "🔄 Fresh installation..."
	docker compose run --rm app php artisan migrate:fresh --seed

# Production deployment
deploy:
	@echo "🚀 Deploying to production..."
	docker compose -f docker-compose.prod.yml up -d --build
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache
	@echo "✅ Deployment complete!"

# Clean up containers and cache
clean:
	@echo "🧹 Cleaning up..."
	docker compose down -v --remove-orphans
	docker system prune -f
	docker compose run --rm app php artisan cache:clear
	docker compose run --rm app php artisan config:clear
	docker compose run --rm app php artisan route:clear
	docker compose run --rm app php artisan view:clear
	@echo "✅ Cleanup complete!"

# Development setup
dev: install start

# Production setup
prod: deploy

# Database commands
db-reset:
	@echo "🗄️ Resetting database..."
	docker compose run --rm app php artisan migrate:fresh

db-backup:
	@echo "💾 Creating database backup..."
	docker compose exec db mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > backup_$(shell date +%Y%m%d_%H%M%S).sql

# AI server commands
ai-start:
	@echo "🤖 Starting AI server (Ollama)..."
	@if ! pgrep -f "ollama serve" > /dev/null; then \
		ollama serve & \
		echo "✅ AI server started"; \
	else \
		echo "✅ AI server already running"; \
	fi

ai-stop:
	@echo "🛑 Stopping AI server..."
	pkill -f "ollama serve" || true
	@echo "✅ AI server stopped"

# Monitoring commands
status:
	@echo "📊 Container status:"
	docker compose ps
	@echo ""
	@echo "🖥️  System resources:"
	docker stats --no-stream

# Quick development workflow
quick-dev: ai-start start
	@echo "🚀 Development environment ready!"
	@echo "🌐 Application: http://localhost:8021"
	@echo "🤖 AI Server: http://localhost:11434"

# Production monitoring
monitor:
	@echo "📈 Monitoring production..."
	docker compose -f docker-compose.prod.yml logs -f --tail=100

# Security commands
security-check:
	@echo "🔒 Running security checks..."
	docker compose run --rm app composer audit
	docker compose run --rm app npm audit

# Performance testing
perf-test:
	@echo "⚡ Running performance tests..."
	docker compose run --rm app php artisan route:list
	docker compose run --rm app php artisan config:cache

# Documentation
docs:
	@echo "📚 Opening documentation..."
	@echo "📖 API Documentation: src/AI_FORECAST_API.md"
	@echo "📋 Stress Test API: src/STRESS_TEST_API.md"
	@echo "🔧 Configuration: .env.example"

# Quick aliases
up: start
down: stop
shell:
	docker compose exec app bash