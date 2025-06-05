<?php

namespace App\Filament\Resources\MissionResource\Pages;

use App\Filament\Resources\MissionResource;
use App\Models\Category;
use App\Models\Person;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMission extends EditRecord
{
    protected static string $resource = MissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('syncSoldier')
                ->label('تحديث الجنود')
                ->action(function () {
                    $soldiers = Person::soldiers()->force()->where('lay_off_date', $this->record->started_at)->pluck('id');
                    $this->record->people()->sync($soldiers);
                    
                    $this->fillForm();

                    Notification::make()
                        ->title('تم تحديث الجنود')
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->hidden(fn () => $this->record->category_id != Category::SOLDIER_BATCH_LAYOFF),
            Actions\Action::make('layoff')
                ->label('تسريح الدفعة')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->people()->update([
                        'is_force' => false,
                        'deleted_date' => $this->record->started_at,
                        'deleted_desc' => 'رديف '.$this->record->started_at->format('d/m/Y'),
                    ]);

                    Notification::make()
                        ->title('تم تسريح الدفعة بنجاح')
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->hidden(fn () => $this->record->category_id != Category::SOLDIER_BATCH_LAYOFF),
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
                    ->hidden(fn (Get $get): bool => $get('category_id') != Category::GENERAL)
                    ->maxLength(255),
                Select::make('category_id')
                    ->label('النوع')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->live()
                    ->preload(),
                DatePicker::make('started_at')
                    ->label(fn (Get $get) => $this->record::startedAtLabel($get('category_id')))
                    ->required(),
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
