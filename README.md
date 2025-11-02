<div class="filament-hidden">

![TeamKit](https://raw.githubusercontent.com/jeffersongoncalves/teamkitv5/5.x/art/jeffersongoncalves-teamkitv5.png)

</div>

# TeamKit Start Kit Filament 5.x and Laravel 12.x

## About TeamKit

TeamKit is a robust starter kit built on Laravel 12.x and Filament 5.x, designed to accelerate the development of modern
web applications with a ready-to-use multi-panel structure.

## Features

- **Laravel 12.x** - The latest version of the most elegant PHP framework
- **Filament 5.x** - Powerful and flexible admin framework
- **Multi-Panel Structure** - Includes three pre-configured panels:
    - Admin Panel (`/admin`) - For system administrators
    - App Panel (`/app`) - For authenticated application users
    - Public Panel (frontend interface) - For visitors
- **Teams (Multitenancy)** - Team management with registration, profile, and team switching directly in the App panel
- **Environment Configuration** - Centralized configuration through the `config/teamkit.php` file

## System Requirements

- PHP 8.2 or higher
- Composer
- Node.js and PNPM

## Installation

Clone the repository
``` bash
laravel new my-app --using=jeffersongoncalves/teamkitv5 --database=mysql
```

###  Easy Installation

TeamKit can be easily installed using the following command:

```bash
php install.php
```

This command automates the installation process by:
- Installing Composer dependencies
- Setting up the environment file
- Generating application key
- Setting up the database
- Running migrations
- Installing Node.js dependencies
- Building assets
- Configuring Herd (if used)

### Manual Installation

Install JavaScript dependencies
``` bash
pnpm install
```
Install Composer dependencies
``` bash
composer install
```
Set up environment
``` bash
cp .env.example .env
php artisan key:generate
```

Configure your database in the .env file

Run migrations
``` bash
php artisan migrate
```
Run the server
``` bash
php artisan serve
```

## Installation with Docker

Clone the repository
```bash
laravel new my-app --using=jeffersongoncalves/teamkitv5 --database=mysql
```

Move into the project directory
```bash
cd my-app
```

Install Composer dependencies
```bash
composer install
```

Set up environment
```bash
cp .env.example .env
```

Configuring custom ports may be necessary if you have other services running on the same ports.

```bash
# Application Port (ex: 8080)
APP_PORT=8080

# MySQL Port (ex: 3306)
FORWARD_DB_PORT=3306

# Redis Port (ex: 6379)
FORWARD_REDIS_PORT=6379

# Mailpit Port (ex: 1025)
FORWARD_MAILPIT_PORT=1025
```

Start the Sail containers
```bash
./vendor/bin/sail up -d
```
You won’t need to run `php artisan serve`, as Laravel Sail automatically handles the development server within the container.

Attach to the application container
```bash
./vendor/bin/sail shell
```

Generate the application key
```bash
php artisan key:generate
```

Install JavaScript dependencies
```bash
pnpm install
```

## Authentication Structure

TeamKit comes pre-configured with a custom authentication system that supports different types of users:

- `Admin` - For administrative panel access
- `User` - For application panel access

## Teams – Multitenancy in the App Panel

Teamkit includes native support for Teams (multitenancy) in the application panel (`/app`). This allows you to isolate data by team and provide a multi‑company/multi‑project experience.

Key points:
- Team model: `App\Models\Team` with fields `user_id` (owner), `name`, and `personal_team`.
- Current user and current team: the user has `current_team_id` and helper methods for team management.
- Tenancy in Filament: the App panel is configured with tenant `Team::class`, tenant route prefix `team`, a team registration page, and a team profile page.

URLs and navigation:
- App panel: `/app`
- Team registration: `/app/team/register` (also accessible via the user menu under Tenancy)
- Team profile: accessible from the Tenancy menu when a team is selected (e.g., `/app/team/{id}/profile` – managed by Filament)
- Team switcher: appears at the top of the panel when the user belongs to 2+ teams.

Creating and managing teams:
- Register a team: at `/app/team/register`, enter the team name. The authenticated user becomes the team owner (`user_id`).
- Edit team profile: via the “Team profile” page, allowing you to change the team `name`.
- Switch teams: use the switcher at the top of Filament. Programmatically: `$user->switchTeam($team)`.

User ↔ team association:
- Team owner: the creator is the owner (field `user_id`).
- Members: the many‑to‑many relationship `User::teams()`/`Team::users()` uses the pivot represented by `App\Models\Membership`.
- Access rules: `User::canAccessTenant($tenant)` validates whether the user belongs to the team (owner or member). The `User::getTenants()` method returns the list for the switcher.

Team invitations (new):
- Invite members from the Team profile page: add emails in the Invitations list. The system prevents duplicates if the email already belongs to the team (owner or member) and enforces uniqueness per team.
- Pending invitations are listed for the invited user in the App panel user menu under “Invitations”. From there, the user can Accept (joins the team immediately) or Cancel (declines) each invite.
- On acceptance, the user is attached to the team membership and the invitation is removed. Declining deletes the invitation without adding the user.
- Admins can view/manage all invitations via the Admin panel resource “Team Invitations”.

Team‑scoped data:
- Middleware: `App\Http\Middleware\ApplyTenantScopes` is prepared for you to apply global scopes to your models, for example:
  ```php
  // Example (uncomment and adapt):
  // SomeModel::addGlobalScope(
  //    fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
  // );
  ```
- Adapt your Resources/queries to always consider the current tenant when applicable.

Relevant migrations:
- `database/migrations/0001_01_01_000005_create_teams_table.php`
    - Creates the `teams` table with: `id`, `user_id` (indexed), `name`, and `personal_team` (boolean), plus timestamps.
- `database/migrations/0001_01_01_000007_create_team_invitations_table.php`
    - Creates the `team_invitations` table to store pending invitations per team and email.

Filament pages related to Teams:
- Registration: `App\Filament\App\Pages\Tenancy\RegisterTeam` (uses `Filament\Pages\Tenancy\RegisterTenant`).
- Profile: `App\Filament\App\Pages\Tenancy\EditTeamProfile` (uses `Filament\Pages\Tenancy\EditTenantProfile`).
- Invitations: `App\Filament\App\Pages\TeamInvitationAccept` — user menu → “Invitations” in the App panel.

App panel configuration (summary):
- `App\Providers\Filament\AppPanelProvider`
    - `->tenant(Team::class)`
    - `->tenantRoutePrefix('team')`
    - `->tenantRegistration(RegisterTeam::class)`
    - `->tenantProfile(EditTeamProfile::class)`
    - Adds a user menu item “Invitations” linking to `TeamInvitationAccept::getUrl()` when a team is active
    - `->tenantMiddleware([ApplyTenantScopes::class], isPersistent: true)`

Tips:
- When creating new Models/Resources that should be isolated by team, remember to relate them to `Team` and apply the scope in the middleware or in your queries.
- If you need to define a default team, `User::currentTeam` is resolved automatically if `current_team_id` is empty (it falls back to the personal team if it exists).

## Development

``` bash
# Run the development server with logs, queues and asset compilation
composer dev

# Or run each component separately
php artisan serve
php artisan queue:listen --tries=1
pnpm run dev
```

## Customization

### Panel Configuration

Panels can be customized through their respective providers:

- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/AppPanelProvider.php`
- `app/Providers/Filament/PublicPanelProvider.php`

Alternatively, these settings are also consolidated in the `config/teamkit.php` file for easier management.

### Themes and Colors

Each panel can have its own color scheme, which can be easily modified in the corresponding Provider files or in the
`teamkit.php` configuration file.

### Configuration File

The `config/teamkit.php` file centralizes the configuration of the starter kit, including:

- Panel routes
- Middleware for each panel
- Branding options (logo, colors)
- Authentication guards

## Resources

TeamKit includes support for:

- User and admin management
- Multi-guard authentication system
- Tailwind CSS integration
- Database queue configuration
- Customizable panel routing and branding

## License

This project is licensed under the [MIT License](LICENSE).

## Credits

Developed by [Jefferson Gonçalves](https://github.com/jeffersongoncalves).
