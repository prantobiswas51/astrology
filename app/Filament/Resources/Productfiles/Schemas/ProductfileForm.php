<?php

namespace App\Filament\Resources\Productfiles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

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
                    ->disk('local')   // ← THIS FIXES THE PRIVATE FOLDER ISSUE
                    ->directory('product_files/uploads')
                    ->getUploadedFileNameForStorageUsing(function ($file) {

                        $original = $file->getClientOriginalName();
                        $name = pathinfo($original, PATHINFO_FILENAME);
                        $ext  = $file->getClientOriginalExtension();

                        // Correct actual folder path
                        $folder = Storage::disk('local')->path('product_files/uploads');

                        $counter = 1;
                        $finalName = $original;

                        // Ensure folder exists
                        if (!file_exists($folder)) {
                            mkdir($folder, 0775, true);
                        }

                        // Avoid overwrite
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
