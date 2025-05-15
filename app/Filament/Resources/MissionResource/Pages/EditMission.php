<?php

namespace App\Filament\Resources\MissionResource\Pages;

use App\Filament\Resources\MissionResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;

class EditMission extends EditRecord
{
    protected static string $resource = MissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->columnSpanFull()
                    ->hidden(fn (Get $get): bool => $get('category_id') != 1)
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('النوع')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
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
            ]);
    }
}
