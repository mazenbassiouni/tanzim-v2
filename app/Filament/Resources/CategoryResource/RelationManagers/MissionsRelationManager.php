<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Events\MissionCreated;
use App\Filament\Resources\MissionResource;
use App\Filament\Resources\RelationManagers\MissionsRelationManager as GenericMissionsRelationManager;
use App\Models\Category;
use App\Models\Mission;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Table;
use Filament\Tables;

class MissionsRelationManager extends GenericMissionsRelationManager
{
    public function table(Table $table): Table
    {
        return MissionResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('العنوان')
                                    ->required()
                                    ->columnSpanFull()
                                    ->hidden(fn (Get $get): bool => $get('category_id') != Category::GENERAL)
                                    ->maxLength(255),
                                Select::make('category_id')
                                    ->label('النوع')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->afterStateHydrated(
                                        fn (Set $set) => $set('category_id', $this->ownerRecord->id)
                                    )
                                    ->live()
                                    ->preload(),
                                DatePicker::make('started_at')
                                    ->label('تاريخ البدء')
                                    ->required()
                                    ->placeholder('اختر تاريخ البدء'),
                                Select::make('people')
                                    ->label(function (Get $get) {
                                        $label = 'بخصوص';
                                        $count = count($get('people'));
                                        if($count){
                                            $label .= ' ('.$count.')';
                                        }
                                        return $label;
                                    })
                                    ->live()
                                    ->multiple()
                                    ->relationship('people', 'name', fn ($query) => $query->orderBy('rank_id'))
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->rank_name)
                                    ->columnSpanFull(),
                                Textarea::make('desc')
                                    ->label('الموضوع')
                                    ->rows(8)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->after(function (Mission $record) {
                        redirect()->to(MissionResource::getUrl('edit', ['record' => $record->id]));
                    }),
            ]);
    }
}