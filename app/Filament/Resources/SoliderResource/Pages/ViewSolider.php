<?php

namespace App\Filament\Resources\SoliderResource\Pages;

use App\Filament\Resources\SoliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSolider extends ViewRecord
{
    protected static string $resource = SoliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
