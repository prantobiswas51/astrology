<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('stripe_api_key')
                    ->label('Stripe API Key'),
                TextInput::make('endpoint_secret')
                    ->label('Endpoint Secret'),
                TextInput::make('maileroo_api_key')
                    ->label('Maileroo API Key'),
            ]);
    }
}
