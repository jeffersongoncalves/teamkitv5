<?php

use JeffersonGoncalves\Filament\Teams\Models\Membership;
use JeffersonGoncalves\Filament\Teams\Models\Team;
use JeffersonGoncalves\Filament\Teams\Models\TeamInvitation;

// config for JeffersonGoncalves/Filament/Teams

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | The guard used to resolve the currently authenticated user across the
    | tenancy pages and middleware shipped with this plugin.
    |
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The Eloquent model that represents your application users. It must use
    | the JeffersonGoncalves\Filament\Teams\Concerns\HasTeams trait.
    |
    */

    'user_model' => 'App\\Models\\User',

    /*
    |--------------------------------------------------------------------------
    | Personal Teams
    |--------------------------------------------------------------------------
    |
    | When enabled, a personal team is automatically created for every new
    | user through the HasTeams trait.
    |
    */

    'personal_teams' => true,

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | These models may be swapped for your own implementations as long as they
    | extend the models provided by this package.
    |
    */

    'models' => [
        'team' => Team::class,
        'team_invitation' => TeamInvitation::class,
        'membership' => Membership::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | The database tables used by this package. Customize them to avoid
    | collisions with existing tables in your application.
    |
    */

    'tables' => [
        'teams' => 'teams',
        'memberships' => 'membership',
        'team_invitations' => 'team_invitations',
    ],

];
