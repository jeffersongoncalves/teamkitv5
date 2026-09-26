<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\RelationManagers\OwnedTeamsRelationManager;
use App\Filament\Admin\Resources\Users\RelationManagers\TeamsRelationManager;
use JeffersonGoncalves\Filament\User\Resources\Users\UserResource as BaseUserResource;

/**
 * The filament-user resource plus the team relations. Registered with
 * UserPlugin::make()->resource(UserResource::class) in the admin panel.
 */
class UserResource extends BaseUserResource
{
    public static function getRelations(): array
    {
        return [
            OwnedTeamsRelationManager::class,
            TeamsRelationManager::class,
        ];
    }
}
