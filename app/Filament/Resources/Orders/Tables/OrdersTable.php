<?php

namespace App\Filament\Resources\Orders\Tables;

use Dom\Text;
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
                            ->label('Email address')->searchable()
                            ->searchable(),
                        
                        TextColumn::make('id')
                            ->label('Order ID')
                            ->prefix('#')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('created_at')
                            ->label('Created At')
                            ->prefix('At : ')
                            ->dateTime('M d, Y H:i:s')
                            ->sortable(),

                        TextColumn::make('notes')->label('Notes')
                        ->color('danger')->limit(150)->prefix('Notes:'),
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
                            ->color(fn($state) => match (strtolower($state)) {
                                'pending' => 'warning',
                                'paid'    => 'success',
                                default   => 'gray',
                            })
                            ->sortable(),
                        TextColumn::make('order_status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'Processing' => 'warning',
                                'Unpaid'     => 'danger',
                                'Completed'    => 'success',
                                default   => 'gray',
                            })
                            ->sortable(),
                    ]),
                ])->columnSpanFull(),
            ])->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->filters([
                // Filter by order status
                \Filament\Tables\Filters\SelectFilter::make('order_status')
                    ->options([
                        'Processing' => 'Processing',
                        'Unpaid' => 'Unpaid',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->label('Order Status'),
                // Filter by payment status
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Unpaid' => 'Unpaid',
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                        'Expired' => 'Expired',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->label('Payment Status'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
