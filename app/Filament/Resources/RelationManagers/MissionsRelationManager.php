<?php

namespace App\Filament\Resources\RelationManagers;

use App\Filament\Resources\MissionResource;
use App\Models\Task;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class MissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'missions';

    protected static ?string $title = 'المتابعات';

    protected static ?string $modelLabel = 'متابعة';

    protected static ?string $pluralModelLabel = 'المتابعات';

    public function form(Form $form): Form
    {
        return $form;
    }

    public function table(Table $table): Table
    {
        return MissionResource::table($table);
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

    public function isReadOnly(): bool
    {
        return false;
    }
}
