<?php

namespace App\Filament\Resources\SpecialityResource\Pages;

use App\Filament\Resources\SpecialityResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageSpecialities extends ManageRecords
{
    protected static string $resource = SpecialityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'officer' => Tab::make('officer')
                ->label('ضباط')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('is_officer', true)->orderBy('name');
                }),
            'sub' => Tab::make('sub')
                ->label('افراد')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->where('is_officer', false)->orderBy('name');
                }),
        ];
    }
}
