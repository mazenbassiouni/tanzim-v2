<?php

namespace App\Filament\Resources\OfficerResource\Pages;

use App\Filament\Resources\OfficerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOfficer extends ViewRecord
{
    protected static string $resource = OfficerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public static function authorizeResourceAccess(): void
    {
        abort_unless(auth()->user()->can('view_officer'), 403);
    }
}
