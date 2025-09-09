<?php

use Filament\Enums\ThemeMode;

return [
    'defaultCurrency' => 'usd',
    'defaultDateDisplayFormat' => 'M j, Y',
    'defaultIsoDateDisplayFormat' => 'L',
    'defaultDateTimeDisplayFormat' => 'M j, Y H:i:s',
    'defaultIsoDateTimeDisplayFormat' => 'LLL',
    'defaultNumberLocale' => null,
    'defaultTimeDisplayFormat' => 'H:i:s',
    'defaultIsoTimeDisplayFormat' => 'LT',
    'theme_mode' => ThemeMode::Light,
    'guest_panel_enabled' => true,
    'admin_panel_enabled' => true,
    'app_panel_enabled' => true,
    'favicon' => [
        'enabled' => true,
        'manifest' => [
            'name' => env('APP_NAME', 'Teamkit'),
            'icons' => [
                '36' => '0.75',
                '48' => '1.0',
                '72' => '1.5',
                '96' => '2.0',
                '144' => '3.0',
                '192' => '4.0',
            ],
        ],
        'logo' => 'resources/images/logo-teamkit.png',
        'favicon' => 'resources/favicon/favicon.ico',
    ],
];
