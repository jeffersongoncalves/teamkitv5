<?php

namespace App\Filament\App\Pages\Tenancy;

use App\Models\Team;
use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

/**
 * @property Team $tenant
 */
class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Team profile';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name'),
                Repeater::make('teamInvitations')
                    ->relationship('teamInvitations')
                    ->simple(
                        TextInput::make('email')
                            ->unique('team_invitations', 'email', modifyRuleUsing: fn ($rule) => $rule->where('team_id', $this->tenant->id))
                            ->rules([fn (): Closure => function (string $attribute, mixed $value, Closure $fail) {
                                if ($this->tenant->users()->where('email', $value)->exists()) {
                                    $fail('The email has already been taken.');
                                }
                                if ($this->tenant->owner()->where('email', $value)->exists()) {
                                    $fail('The email has already been taken.');
                                }
                            }])
                            ->email()
                            ->required(),
                    )
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        $data['team_id'] = $this->tenant->id;

                        return $data;
                    }),
            ]);
    }
}
