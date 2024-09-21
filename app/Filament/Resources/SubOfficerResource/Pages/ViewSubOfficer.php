<?php

namespace App\Filament\Resources\SubOfficerResource\Pages;

use App\Filament\Resources\SubOfficerResource;
use App\Models\Person;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubOfficer extends ViewRecord
{
    protected static string $resource = SubOfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
