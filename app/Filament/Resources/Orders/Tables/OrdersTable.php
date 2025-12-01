<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    // LEFT SIDE
                    Stack::make([
                        TextColumn::make('email')
                            ->label('Email address')
                            ->searchable(),

                        TextColumn::make('user.name')
                            ->label('Username')
                            ->sortable(),

                        TextColumn::make('created_at')
                            ->label('Created At')
                            ->dateTime('M d, Y H:i')
                            ->sortable(),
                    ]),

                    // RIGHT SIDE
                    Stack::make([
                        TextColumn::make('total_amount')
                            ->label('Amount')
                            ->money('usd')
                            ->sortable(),

                        TextColumn::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => match (strtolower($state)) {
                                'pending' => 'warning',
                                'paid'    => 'success',
                                default   => 'gray',
                            })
                            ->sortable(),
                        TextColumn::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('M d, Y H:i')
                            ->sortable(),
                    ]),
                ])->columnSpanFull(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
