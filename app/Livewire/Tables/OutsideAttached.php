<?php

namespace App\Livewire\Tables;

use App\Filament\Resources\MissionResource;
use App\Models\Person;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OutsideAttached extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $category_id = 21;

    public function table(Table $table): Table
    {
        $query = Person::where('is_force', 1)->whereHas('missions', function ($query) {
                $query->where('category_id', $this->category_id)
                    ->whereHas('tasks', function ($query){
                        $query->where('status', '<>', 'done');
                    });
            })
            ->orderBy('rank_id')->with([
                'rank',
                'missions' => function ($query) {
                    $query->where('category_id', $this->category_id)
                        ->whereHas('tasks', function ($query){
                            $query->where('status', '<>', 'done');
                        });
                }
            ]);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('name')
                            ->label('رتبة أو درجة/ اسم')
                            ->formatStateUsing(fn (Person $record): string => $record->rank_name),
                TextColumn::make('id')
                    ->label('الجهة')
                    ->formatStateUsing(fn (Person $record): string => $record->missions->first()->desc ?? '')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                 Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->modalContent(fn (Person $record): View => view('filament.infolists.components.view-mission', [
                        'mission' => $record->missions->first()
                    ]))
                    ->extraModalFooterActions([
                        Action::make('edit')
                            ->label('تعديل')
                            ->url(fn (Person $record): string => MissionResource::getUrl('edit', ['record' => $record->missions->first()]))
                            ->openUrlInNewTab(),
                    ]),
                Tables\Actions\Action::make('edit')
                    ->iconButton()
                    ->label('تعديل')
                    ->icon('heroicon-s-pencil-square')
                    ->url(fn (Person $record): string => MissionResource::getUrl('edit', ['record' => $record->missions->first()]))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false)
            ->emptyStateHeading('لايوجد');
    }

    public function render()
    {
        return view('livewire.tables.outside-attached');
    }
}
