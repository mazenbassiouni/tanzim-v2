<?php

namespace App\Filament\Resources\SoliderResource\Pages;

use App\Filament\Resources\SoliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoliders extends ListRecords
{
    protected static string $resource = SoliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
