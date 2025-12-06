<?php

namespace App\Filament\Resources\Productfiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name', fn($query) => $query->where('type', 'digital'))
                    ->required()
                    ->preload(),
                TextInput::make('file_name')
                    ->required(),
                FileUpload::make('file_path')
                    ->directory('product_files/uploads')
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $original = $file->getClientOriginalName();
                        $name = pathinfo($original, PATHINFO_FILENAME);
                        $ext  = $file->getClientOriginalExtension();

                        $folder = storage_path('app/product_files/uploads');
                        $counter = 1;

                        $finalName = $original;

                        // Check for existing file and add suffix
                        while (file_exists($folder . '/' . $finalName)) {
                            $finalName = $name . '_' . $counter . '.' . $ext;
                            $counter++;
                        }

                        return $finalName;
                    })
                    ->required(),

            ]);
    }
}
