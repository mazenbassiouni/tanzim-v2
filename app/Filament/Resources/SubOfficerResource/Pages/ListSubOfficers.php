<?php

namespace App\Filament\Resources\SubOfficerResource\Pages;

use App\Filament\Resources\SubOfficerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubOfficers extends ListRecords
{
    protected static string $resource = SubOfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
