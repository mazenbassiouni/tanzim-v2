<?php

namespace App\Filament\Resources\SubOfficerResource\Pages;

use App\Filament\Resources\SubOfficerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubOfficer extends EditRecord
{
    protected static string $resource = SubOfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
