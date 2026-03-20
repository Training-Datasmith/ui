# Architecture: ui

## Purpose

A Laravel package providing scaffolding commands and authentication boilerplate generators for the frontend UI layer. Publishes pre-built Bootstrap/Vue/React authentication views, routes, and controllers with a single Artisan command.

## Directory Structure

```
src/
  Ui_Service_Provider.php   - Registers Artisan commands with the Laravel container
  Ui_Command.php            - `php artisan ui {bootstrap|vue|react}` — scaffolds frontend assets
  Auth_Command.php          - `php artisan ui:auth` — publishes auth views and routes
  Auth_Route_Methods.php    - Trait providing the auth route registration macro
  Controllers_Command.php   - `php artisan ui:controllers` — publishes auth controller stubs
  Auth/                     - Auth controller stubs published to the application
  Presets/                  - Bootstrap, Vue, and React preset implementations
stubs/                      - View and route stub files published to the application
```

## Key Design Decisions

- **Preset pattern**: Each frontend framework (Bootstrap, Vue, React) is encapsulated in a `Preset` class that knows which stubs to publish and which `package.json` dependencies to inject, making it easy to add new presets.
- **Scaffolding only**: The package generates and publishes files into the application; it does not add runtime middleware or service bindings. After running the command, the package is no longer needed at runtime.
- **Artisan command surface**: All functionality is exposed through Artisan commands (`ui`, `ui:auth`, `ui:controllers`), keeping the API familiar to Laravel developers.

## Extension Points

- Create a custom `Preset` class and register it via `Ui_Command::macro('myPreset', MyPreset::class)` to add a new frontend scaffold.

## Dependency Flow

```
php artisan ui vue --auth
  └─> Ui_Command::handle()
        └─> Vue_Preset::install() — update package.json, publish JS/SCSS stubs
        └─> Auth_Command::handle() — publish auth views, routes, controllers
```
