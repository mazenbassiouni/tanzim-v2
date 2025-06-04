<?php

namespace App\Livewire\Infolists;

use App\Models\Mission;
use App\Models\Task;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ViewMission extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public Mission $mission;

    public function mount(Mission $mission)
    {
        $this->mission = $mission;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->record($this->mission)
                    ->schema([
                        Fieldset::make('Mission Details')
                            ->label('')
                            ->schema([
                                TextEntry::make('category.name')
                                    ->label('العنوان')
                                    ->color('primary')
                                    ->weight(FontWeight::Bold)
                                    ->formatStateUsing(fn (string $state): string => $this->mission->category->id != 1 ? $state : $this->mission->title),
                                TextEntry::make('started_at')
                                    ->label('تاريخ البدء')
                                    ->date('l d/m/Y')
                                    ->color('primary')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('people.name')
                                    ->label('بخصوص')
                                    ->html()
                                    ->formatStateUsing(function (string $state): string {
                                        $person = $this->mission->people->where('name', $state)->first();
                                        return '<a href="'.$person->getViewLink().'" target="_blank">'.$person->rank_name.'</a>';
                                    })
                                    ->listWithLineBreaks()
                                    ->limitList(3)
                                    ->expandableLimitedList()
                                    ->bulleted()
                                    ->color('primary')
                                    ->weight(FontWeight::Bold)
                                    ->hidden(fn (): bool => !$this->mission->people->count())
                                    ->bulleted(fn (): bool => $this->mission->people->count() > 1),
                                TextEntry::make('desc')
                                    ->view('filament.infolists.entries.description')
                                    ->label('')
                                    ->icon('heroicon-s-arrow-turn-down-left')
                                    ->color('primary')
                                    ->weight(FontWeight::Bold)
                                    ->hidden(fn (): bool => !$this->mission->desc)
                                    ->iconColor('black')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        RepeatableEntry::make('tasks')
                            ->label('')
                            ->schema([
                                TextEntry::make('title')
                                    ->tooltip(fn (Task $record): string => $record->desc ?? '')
                                    ->label('')
                                    ->bulleted()
                                    ->color(function (Task $record): string {
                                        switch ($record->status) {
                                            case 'done':
                                                return 'success';
                                            case 'active':
                                                return 'danger';
                                            default:
                                                return 'warning';
                                        }
                                    })
                                    ->weight(FontWeight::Bold)
                                    ->columnSpan(3),
                                TextEntry::make('')
                                    ->label('')
                                    ->formatStateUsing(function (Task $record): string {
                                        switch ($record->status) {
                                            case 'done':
                                                return $record->done_at?->translatedFormat('l d/m/Y') ?? '';
                                            case 'active':
                                                return $record->due_to?->translatedFormat('l d/m/Y') ?? '';
                                            default:
                                                return '';
                                        }
                                    })
                                    ->color(function (Task $record): string {
                                        switch ($record->status) {
                                            case 'done':
                                                return 'success';
                                            case 'active':
                                                return 'danger';
                                            default:
                                                return 'warning';
                                        }
                                    })
                                    ->weight(FontWeight::Bold),
                            ])
                            ->contained(false)
                            ->columns(4)
                            ->grid(1),
                    ])
                    ->columns(1);
    }

    public function render()
    {
        return view('livewire.infolists.view-mission');
    }
}
