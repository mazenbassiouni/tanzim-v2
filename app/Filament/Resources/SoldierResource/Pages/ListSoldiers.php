<?php

namespace App\Filament\Resources\SoldierResource\Pages;

use App\Filament\Resources\SoldierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoldiers extends ListRecords
{
    protected static string $resource = SoldierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
