<?php

namespace App\Filament\Resources\SoliderResource\Pages;

use App\Filament\Resources\SoliderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolider extends EditRecord
{
    protected static string $resource = SoliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
