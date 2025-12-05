<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\Layout\Stack;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $title = 'Order Items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),

                Textarea::make('extra_information')
                    ->label('Extra Information')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Stack::make([
                    TextColumn::make('product.name')
                        ->label('Product')
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('quantity')
                        ->numeric()
                        ->sortable(),

                    TextColumn::make('price')
                        ->money('USD')
                        ->sortable(),

                    TextColumn::make('total')
                        ->label('Total')
                        ->money('USD')
                        ->getStateUsing(fn($record) => $record->quantity * $record->price),
                ]),

                TextColumn::make('extra_information')
                    ->label('Extra Information')
                    ->formatStateUsing(function ($state, $record) {
                        if (empty($state)) {
                            return 'N/A';
                        }

                        // Decode JSON stored as string
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (!is_array($decoded)) {
                                return 'N/A';
                            }
                            $state = $decoded;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | DIGITAL PRODUCT HANDLING
                        | Detect using product->type == 'digital'
                        |--------------------------------------------------------------------------
                        */
                        
                        if ($record->product && $record->product->type === 'digital') {
                            if (isset($state['file_ids']) && is_array($state['file_ids'])) {
                                $files = \App\Models\ProductFile::whereIn('id', $state['file_ids'])->get();

                                if ($files->isEmpty()) {
                                    return 'Files not found';
                                }

                                $result = [];
                                foreach ($files as $file) {
                                    $result[] = "<strong>File:</strong> " . e($file->file_name);
                                }

                                return implode('<br>', $result);
                            }

                            return 'No files';
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | DEFAULT HANDLING (your original behavior)
                        |--------------------------------------------------------------------------
                        */
                        $formatted = [];

                        foreach ($state as $key => $value) {
                            if (!is_scalar($value)) {
                                continue;
                            }

                            $label = ucfirst(str_replace('_', ' ', $key));
                            $formatted[] = "<strong>{$label}:</strong> " . e($value);
                        }

                        return !empty($formatted) ? implode('<br>', $formatted) : 'N/A';
                    })
                    ->html()
                    ->wrap()
                    ->toggleable(),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
