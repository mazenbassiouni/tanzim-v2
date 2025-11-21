<?php

namespace App\Filament\Resources\PersonFunctionResource\Pages;

use App\Filament\Resources\PersonFunctionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePersonFunctions extends ManageRecords
{
    protected static string $resource = PersonFunctionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
