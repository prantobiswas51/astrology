<?php

namespace App\Filament\Resources\Productfiles;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\ProductFile;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Productfiles\Pages\EditProductfile;
use App\Filament\Resources\Productfiles\Pages\ListProductfiles;
use App\Filament\Resources\Productfiles\Pages\CreateProductfile;
use App\Filament\Resources\Productfiles\Schemas\ProductfileForm;
use App\Filament\Resources\Productfiles\Tables\ProductfilesTable;

class ProductfileResource extends Resource
{
    protected static ?string $model = ProductFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ProductFile';

    public static function form(Schema $schema): Schema
    {
        return ProductfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductfiles::route('/'),
            'create' => CreateProductfile::route('/create'),
            'edit' => EditProductfile::route('/{record}/edit'),
        ];
    }
}
