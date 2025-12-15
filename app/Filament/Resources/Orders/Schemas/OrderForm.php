<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')->disabled(),
                TextInput::make('id')
                    ->label('Order ID')
                    ->disabled(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0.0),
                Select::make('status')->options([
                    'Pending' => 'Pending',
                    'Paid'    => 'Paid',
                    'Canceled' => 'Canceled',
                ])
                    ->required()
                    ->default('Pending'),
                Select::make('order_status')->options([
                    'Unpaid' => 'Unpaid',
                    'Processing' => 'Processing',
                    'Completed' => 'Completed',
                    'Canceled' => 'Canceled',
                ])->required()->default('Processing'),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
