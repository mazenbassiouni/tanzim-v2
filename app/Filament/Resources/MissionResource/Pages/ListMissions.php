<?php

namespace App\Filament\Resources\MissionResource\Pages;

use App\Filament\Resources\MissionResource;
use App\Models\Mission;
use App\Models\Task;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMissions extends ListRecords
{
    protected static string $resource = MissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $active_count = Mission::whereRelation('tasks', 'status', 'active')
                            ->count();
        $pending_count = Mission::whereRelation('tasks', 'status', 'pending')
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
}
