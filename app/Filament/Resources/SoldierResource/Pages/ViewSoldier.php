<?php

namespace App\Filament\Resources\SoldierResource\Pages;

use App\Filament\Resources\SoldierResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSoldier extends ViewRecord
{
    protected static string $resource = SoldierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public static function authorizeResourceAccess(): void
    {
        abort_unless(auth()->user()->can('view_soldier'), 403);
    }
}
