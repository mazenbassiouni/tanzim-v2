<?php

namespace App\Filament\Resources\SoldierResource\Pages;

use App\Filament\Resources\SoldierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSoldier extends EditRecord
{
    protected static string $resource = SoldierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
