<?php

namespace App\Filament\Widgets;

use App\Models\Officer;
use App\Models\Soldier;
use App\Models\SubOfficer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ForceOverview extends BaseWidget
{
    protected static ?string $permission = 'widget_ForceOverview';

    protected static ?string $navigationLabel = 'التمام';

    protected static ?string $headding = 'اجمالي القوة';

    public static function canView(): bool
    {
        return Auth::user()->can(static::$permission);
    }

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $officers = Officer::whereIsMission(false)->whereIsForce(true)->count();
        $subOfficers = SubOfficer::whereIsMission(false)->whereIsForce(true)->count();
        $soldiers = Soldier::whereIsMission(false)->whereIsForce(true)->count();

        $extraOfficers = Officer::whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 60])->whereHas('tasks', function ($query){
                                        $query->where('status', '<>', 'done');
                                    });
                                })->count();

        $extraSubOfficers = SubOfficer::whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 60])->whereHas('tasks', function ($query){
                                        $query->where('status', '<>', 'done');
                                    });
                                })->count();

        $extraSoldiers = Soldier::whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 60])->whereHas('tasks', function ($query){
                                        $query->where('status', '<>', 'done');
                                    });
                                })->count();

        return [
            // Stat::make('القوة', $officers + $subOfficers + $soldiers),
            Stat::make('ضباط', $officers)
                ->description($extraOfficers)
                ->descriptionIcon('heroicon-m-plus')
                ->color('success'),
            Stat::make('الافراد', $subOfficers + $soldiers),
            Stat::make('ضباط صف', $subOfficers)
                ->description($extraSubOfficers)
                ->descriptionIcon('heroicon-m-plus')
                ->color('success'),
            Stat::make('جنود', $soldiers)
                ->description($extraSoldiers)
                ->descriptionIcon('heroicon-m-plus')
                ->color('success'),
        ];
    }

    public function getHeading(): string
    {
        return static::$headding ?? __('filament-shield::filament-shield.widgets.ForceOverview');
    }
}
