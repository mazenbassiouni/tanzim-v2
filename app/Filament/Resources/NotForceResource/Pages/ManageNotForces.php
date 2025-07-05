<?php

namespace App\Filament\Resources\NotForceResource\Pages;

use App\Filament\Resources\NotForceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNotForces extends ManageRecords
{
    protected static string $resource = NotForceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
