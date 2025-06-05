<?php

namespace App\Filament\Resources\MissionResource\Pages;

use App\Filament\Resources\MissionResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewMission extends ViewRecord
{
    protected static string $resource = MissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('category.name')
                    ->label('العنوان')
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn (string $state): string => $this->record->display_title),
                TextEntry::make('started_at')
                    ->label('تاريخ البدء')
                    ->date('l d/m/Y')
                    ->color('primary')
                    ->weight(FontWeight::Bold),
                TextEntry::make('people.name')
                    ->label('بخصوص')
                    ->html()
                    ->formatStateUsing(function (string $state): string {
                        $person = $this->record->people->where('name', $state)->first();
                        return '<a href="'.$person->getViewLink().'" target="_blank">'.$person->rank_name.'</a>';
                    })
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->bulleted()
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->hidden(fn (): bool => !$this->record->people->count())
                    ->bulleted(fn (): bool => $this->record->people->count() > 1),
                TextEntry::make('desc')
                    ->view('filament.infolists.entries.description')
                    ->label('')
                    ->icon('heroicon-s-arrow-turn-down-left')
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->hidden(fn (): bool => !$this->record->desc)
                    ->iconColor('black')
                    ->columnSpanFull(),
            ]);
    }
}
