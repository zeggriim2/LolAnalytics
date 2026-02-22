# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

LolAnalytics is a League of Legends match analytics application built with Symfony 7.3 and PHP 8.4+. It ingests match data from the Riot Games API using the `zeggriim/riot-api-datadragon` package and stores it for analysis.

The application uses Docker with FrankenPHP (a modern PHP application server with Caddy) for development and production environments.

## Architecture

The codebase follows **Domain-Driven Design (DDD)** principles with a **modular bounded context** structure:

### Bounded Contexts

Each context under `src/` follows this internal structure:
- `Domain/`: Models, value objects, repository interfaces, domain events
- `Application/`: CQRS commands, queries, handlers, DTOs, use cases, ports (interfaces to external services)
- `Infrastructure/`: Doctrine entities, repositories, external API adapters/clients
- `Presentation/Api/` and `Presentation/Console/`: HTTP controllers and CLI commands

1. **Match** (`src/Match/`): Core match ingestion and querying
2. **Champion** (`src/Champion/`): Champion data and skin management
3. **GameData** (`src/GameData/`): Generic League of Legends game data (versions, assets)
4. **Summoner** (`src/Summoner/`): Summoner/player profile data
5. **SharedContext** (`src/SharedContext/`): Cross-cutting concerns
   - `Application/Bus/`: Command, query, and event bus interfaces
   - `Infrastructure/Bus/`: Messenger-based bus implementations
   - `Infrastructure/Framework/Symfony/`: Symfony kernel integration
   - `Domain/Pagination/`, `Domain/ValueObjet/`: Shared domain primitives

### CQRS Architecture

The application uses CQRS (Command Query Responsibility Segregation) with Symfony Messenger:

- **Command Bus** (`command.bus`): Handles write operations (e.g., `IngestMatchCommand`)
- **Query Bus** (`query.bus`): Handles read operations (e.g., `GetMatchByIdQuery`, `ListMatchesQuery`)
- **Event Bus** (`event.bus`): Handles domain events with `allow_no_handlers` and `dispatch_after_current_bus` middleware
- **Message Bus** (`message.bus`): General message handling

All buses are configured in `config/packages/messenger.yaml`. The default bus is `command.bus`.

### Persistence

- **ORM**: Doctrine ORM with attribute-based mapping
- **Database**: MySQL 8
- **Entities**: Located in `src/Match/Infrastructure/Persistence/Doctrine/Entity/`
  - `MatchEntity`: Represents a League of Legends match
  - `ParticipantEntity`: Represents a player's participation in a match
- **Migrations**: Located in `migrations/` directory
- **Repository Pattern**: Domain repository interfaces in `Domain/Repository/`, implemented by Doctrine repositories in `Infrastructure/Persistence/Doctrine/Repository/`

### Domain Models vs Entities

The architecture separates domain models from persistence:
- **Domain Models** (`src/Match/Domain/Model/`): Rich domain objects with business logic
- **Doctrine Entities** (`src/Match/Infrastructure/Persistence/Doctrine/Entity/`): Persistence layer, mapped to database

## Development Commands

### Docker Operations
```bash
make build          # Build Docker images from scratch
make up             # Start containers in detached mode
make start          # Build and start containers
make down           # Stop containers
make logs           # Show live logs
make sh             # Connect to FrankenPHP container (sh)
make bash           # Connect to FrankenPHP container (bash)
```

### Symfony Commands
```bash
make sf c=<command>           # Run any Symfony console command
make cc                       # Clear cache (alias for cache:clear)

# Examples:
make sf c=doctrine:migrations:migrate
make sf c=messenger:consume
```

### Testing
```bash
make test                                    # Run all PHPUnit tests
make test c="--filter=TestName"              # Run specific test
make test-unit                               # Run only tests/Unit/
make test-functional                         # Run only tests/Functional/
make test-coverage                           # HTML coverage report in var/coverage/
make test-coverage-text                      # Coverage summary in terminal
```

Tests are organized under `tests/`:
- `tests/Unit/`: Pure unit tests (per bounded context)
- `tests/Functional/`: Integration tests using real DB/services
- `tests/Story/`: Story/scenario-based tests
- `tests/Factory/`: Shared test factories

### Code Quality
```bash
make qa                       # Run all quality tools (PHP CS Fixer + PHPStan + ESLint + Prettier)
make cs-check                 # Check coding standards (dry-run)
make cs-fix                   # Fix coding standards
make phpstan                  # Run PHPStan static analysis (level 8)
make phpstan-baseline         # Generate PHPStan baseline
make phpstan-clear            # Clear PHPStan cache
```

PHP quality tools run via the `jakzal/phpqa:php8.4-alpine` Docker image (no containers needed).

### Composer
```bash
make composer c=<command>     # Run composer commands
make vendor                   # Install vendors for production

# Examples:
make composer c="require symfony/validator"
make composer c="update"
```

### Database Migrations
```bash
make sf c=doctrine:migrations:migrate           # Run migrations
make sf c=doctrine:migrations:diff              # Generate migration from entity changes
make sf c=doctrine:migrations:status            # Check migration status
```

## Frontend Stack

The frontend is a **Vue 3 + TypeScript** SPA served by a dedicated `node` container:
- **Build tool**: Vite 5
- **UI framework**: Vue 3 with Vue Router and Pinia (state management)
- **Styling**: Tailwind CSS 4
- **Charts**: Chart.js via vue-chartjs
- **Source**: `assets/` directory

### Frontend Commands
```bash
make yarn c=<command>       # Run arbitrary yarn command
make yarn-install           # Install node dependencies
make yarn-dev               # Start Vite dev server (hot reload on port 5173)
make yarn-build             # Build assets for production
make node-sh                # Shell into the Node container
make node-logs              # Tail Node container logs

# Front quality
make front-lint             # ESLint on assets/
make front-lint-fix         # ESLint auto-fix
make front-format           # Prettier format
make front-format-check     # Prettier check (dry-run)
make front-type-check       # TypeScript tsc --noEmit
make front-qa               # All front quality tools
```

## Docker Services

The application runs three Docker services (see `compose.yaml` + `compose.override.yaml`):

1. **php**: FrankenPHP application server (ports 80, 443 HTTP/3)
2. **node**: Node 22 container running the Vite dev server (port 5173)
3. **database**: MySQL 8 database (port 3306 exposed in dev)

All services use environment variables defined in `.env` (never commit secrets).

## Important Notes

### PHPStan
- Configuration: `phpstan.neon` (level 8)
- Baseline: `phpstan-baseline.neon` (contains ignored errors for Doctrine entities)
- Run with 1G memory limit via Makefile

### Messenger Configuration
- Currently uses `sync` transport (configured in `messenger.yaml`)
- Redis transport configured but commented out (no redis/messenger_worker service active)

### Service Configuration
Service bindings are in `config/services.yaml`:
- Repository interfaces are aliased to Doctrine implementations
- CQRS bus interfaces aliased to Messenger implementations
- Domain models excluded from service container
- All services use autowiring and autoconfiguration

### Doctrine Mapping
- Match context entities mapped via attributes
- Mapping configuration in `config/packages/doctrine.yaml`
- Entity path: `src/Match/Infrastructure/Persistence/Doctrine/Entity`
- Namespace: `App\Match\Infrastructure\Persistence\Doctrine\Entity`
- Alias: `Match`

### Environment Variables
Key variables in `.env`:
- `DATABASE_URL`: MySQL connection string
- `API_RIOT_TOKEN`: Riot Games API token (DO NOT COMMIT)
- `MESSENGER_TRANSPORT_DSN`: Redis connection for message queue
- `APP_ENV`: Environment (dev/prod/test)
- `APP_SECRET`: Symfony application secret

## Testing

- PHPUnit configuration: `phpunit.dist.xml`
- Test environment: `APP_ENV=test`
- Tests run inside Docker container via `make test`
- Strict mode enabled: fails on deprecation, notice, and warning
