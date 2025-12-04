<?php

namespace App\Filament\Resources\Productfiles\Pages;

use App\Filament\Resources\Productfiles\ProductfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductfile extends EditRecord
{
    protected static string $resource = ProductfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
