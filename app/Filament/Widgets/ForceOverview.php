<?php

namespace App\Filament\Widgets;

use App\Models\Person;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ForceOverview extends BaseWidget
{

    protected static ?string $permission = 'widget_ForceOverview';

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
        $officers = Person::where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count();
        $subOfficers = Person::where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count();
        $soldiers = Person::where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count();

        $extraOfficers = Person::where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 59])->whereHas('tasks', function ($query){
                                        $query->where('status', '<>', 'done');
                                    });
                                })->count();

        $extraSubOfficers = Person::where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 59])->whereHas('tasks', function ($query){
                                        $query->where('status', '<>', 'done');
                                    });
                                })->count();

        $extraSoldiers = Person::where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(false)
                                ->whereHas('missions', function ($query){
                                    $query->whereIn('category_id', [20, 59])->whereHas('tasks', function ($query){
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
}
