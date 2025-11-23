<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    // LEFT SIDE (2/3)
                    Section::make('Product Details')
                        ->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('slug')->required(),
                            Textarea::make('short_description'),
                            Textarea::make('description')->columnSpanFull(),
                            CodeEditor::make('custom_html')->columnSpanFull(),
                            CodeEditor::make('fields'),
                        ])
                        ->columns(1)
                        ->columnSpan(2),  // ⬅️ TAKE 2/3 width

                    // RIGHT SIDE (1/3)
                    Section::make('Product Images')
                        ->schema([
                            Select::make('category.name')->relationship('category', 'name')->required(),
                            Select::make('type')
                                ->options([
                                    'simple' => 'Simple',
                                    'grouped' => 'Grouped',
                                    'booking' => 'Booking',
                                    'digital' => 'Digital'
                                ])
                                ->required(),
                            TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->default(0.0)
                                ->prefix('$'),
                            TextInput::make('sale_price')
                                ->numeric()
                                ->prefix('$'),
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'private' => 'Private',
                                    'published' => 'Published',
                                ])->required(),
                            FileUpload::make('image1_path')->disk('public')->directory('products/images'),
                            FileUpload::make('image2_path')->disk('public')->directory('/products/images'),
                            FileUpload::make('image3_path')->disk('public')->directory('products/images'),
                            FileUpload::make('image4_path')->disk('public')->directory('products/images'),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
                ])->columnSpanFull()
            ]);
    }
}
