<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\FileUpload;

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
                            Repeater::make('fields')
                                ->schema([

                                    // Row 1 (3 columns)
                                    Grid::make(3)->schema([
                                        TextInput::make('label')->required(),
                                        TextInput::make('name')->required(),
                                        TextInput::make('id')->required(),
                                    ]),

                                    // Row 2 (3 columns)
                                    Grid::make(3)->schema([
                                        Select::make('type')
                                            ->options([
                                                'text'     => 'Text',
                                                'email'    => 'Email',
                                                'number'   => 'Number',
                                                'textarea' => 'Textarea',
                                                'select'   => 'Dropdown',
                                                'radio'    => 'Radio',
                                                'date'     => 'Date',
                                                'checkbox' => 'Checkbox',
                                            ])
                                            ->required(),

                                        TextInput::make('placeholder')
                                            ->nullable(),
                                        TextInput::make('order')->numeric()->default(1),
                                        // last column of second row
                                    ]),

                                    // Options & order in full width under the grid
                                    Grid::make(2)->schema([
                                        Textarea::make('options')
                                            ->label('Options')
                                            ->helperText('Comma-separated options for select or radio fields')
                                            ->visible(fn($get) => in_array($get('type'), ['select', 'radio'])),


                                        Toggle::make('required')
                                            ->label('Required'),
                                    ])->columnSpanFull(),

                                ])
                                ->columnSpanFull(),


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
                            FileUpload::make('image2_path')->disk('public')->directory('products/images'),
                            FileUpload::make('image3_path')->disk('public')->directory('products/images'),
                            FileUpload::make('image4_path')->disk('public')->directory('products/images'),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
                ])->columnSpanFull()
            ]);
    }
}
