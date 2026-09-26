<?php

namespace App\Models;

use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use JeffersonGoncalves\Filament\Teams\Concerns\HasTeamsFilament;
use JeffersonGoncalves\Filament\User\Models\User as BaseUser;

/**
 * Columns, casts, factory, observer, Filament panel access and avatar come from
 * jeffersongoncalves/laravel-user + jeffersongoncalves/filament-user.
 * Teams / tenancy come from jeffersongoncalves/filament-teams.
 *
 * @property int|null $current_team_id
 */
class User extends BaseUser implements HasDefaultTenant, HasTenants
{
    use HasTeamsFilament;

    protected $fillable = [
        'status',
        'name',
        'email',
        'password',
        'avatar_url',
        'custom_fields',
        'locale',
        'theme_color',
        'current_team_id',
    ];
}
