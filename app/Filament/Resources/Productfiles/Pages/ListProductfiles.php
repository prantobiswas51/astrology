<?php

namespace App\Filament\Resources\Productfiles\Pages;

use App\Filament\Resources\Productfiles\ProductfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductfiles extends ListRecords
{
    protected static string $resource = ProductfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
