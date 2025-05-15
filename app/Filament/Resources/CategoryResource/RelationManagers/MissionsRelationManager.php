<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\MissionResource;
use App\Models\Task;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'missions';

    protected static ?string $title = 'المتابعات';

    protected static ?string $modelLabel = 'متابعة';

    protected static ?string $pluralModelLabel = 'المتابعات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('people.name')
                    ->label('رتبة أو درجة/ اسم')
                    ->formatStateUsing(fn (Model $record, string $state): string => $record->people->where('name', $state)->first()->rank_name)
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->bulleted(fn (Model $record): bool => $record->people->count() > 1),
                TextColumn::make('due_to')
                    ->label('تاريخ الاستحقاق')
                    ->date('l d/m/Y'),
            ])
            ->recordClasses(function (Model $record) {
                if($record->due_to){
                    if($record->due_to->lt(today())){
                        return 'bg-red-100 dark:bg-red-900';
                    } elseif($record->due_to->eq(today())){
                        return 'bg-yellow-100 dark:bg-yellow-900';
                    } elseif($record->due_to->eq(today()->addDays())){
                        return 'bg-blue-100 dark:bg-blue-900';
                    }
                }
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->modalContent(fn (Model $record): View => view('filament.infolists.components.view-mission', [
                        'mission' => $record
                    ]))
                    ->extraModalFooterActions([
                        Action::make('edit')
                            ->label('تعديل')
                            ->url(fn (Model $record): string => MissionResource ::getUrl('custom-edit', ['record' => $record]))
                            ->openUrlInNewTab(),
                    ]),
                Tables\Actions\Action::make('custom-edit')
                    ->iconButton()
                    ->label('تعديل')
                    ->icon('heroicon-s-pencil-square')
                    ->url(fn (Model $record): string => MissionResource::getUrl('custom-edit', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا يوجد متابعات');
    }

    public function getTabs(): array
    {
        $active_count = $this->ownerRecord->missions()
                            ->whereRelation('tasks', 'status', 'active')
                            ->count();
        $pending_count = $this->ownerRecord->missions()
                            ->whereRelation('tasks', 'status', 'pending')
                            ->whereDoesntHave('tasks', function (Builder $query) {
                                $query->where('status', 'active');
                            })->count();
        return [
            'done' => Tab::make('done')
                ->label('منتهية')
                ->modifyQueryUsing( function(Builder $query) {
                    $query->whereDoesntHave('tasks', function (Builder $query) {
                        $query->where('status', 'pending')
                            ->orWhere('status', 'active');
                    })
                    ->orderBy(
                        Task::select('done_at')
                            ->whereColumn('missions.id', 'tasks.mission_id')
                            ->where('status', 'done')
                            ->orderBy('done_at', 'desc')
                            ->limit(1), 'desc'
                    );
                }),
            'pending' => Tab::make('pending')
                ->label('معلقة'.' ('.$pending_count.')')
                ->modifyQueryUsing( function(Builder $query) {
                        $query->whereRelation('tasks', 'status', 'pending')
                        ->whereDoesntHave('tasks', function (Builder $query) {
                            $query->where('status', 'active');
                        });
                }),
            'active' => Tab::make('active')
                ->label('جارية'.' ('.$active_count.')')
                ->modifyQueryUsing( function(Builder $query) {
                        $query->whereRelation('tasks', 'status', 'active')
                            ->orderBy(
                                Task::select('due_to')
                                    ->whereColumn('tasks.mission_id', 'missions.id')
                                    ->where('status', 'active')
                                    ->orderBy('due_to')
                                    ->limit(1)
                            );
                }),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'active';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('sss');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
