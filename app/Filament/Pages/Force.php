<?php

namespace App\Filament\Pages;
use App\Filament\Widgets\ForceOverview;
use App\Models\Person;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class Force extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'التمام';
    
    protected static ?string $title = 'التمام';

    protected static string $view = 'filament.pages.force';

    public array $tamam;

    public int $category_id = 59;

    protected function getHeaderWidgets(): array
    {
        return [
            ForceOverview::class,
        ];
    }

    public function peopleQuery(int $category_id)
    {
        return Person::where('is_force', 1)->whereHas('missions', function ($query) use ($category_id) {
                $query->where('category_id', $category_id)
                    ->whereHas('tasks', function ($query){
                        $query->where('status', '<>', 'done');
                    });
            })
            ->orderBy('rank_id')->with([
                'rank',
                'missions' => function ($query) use ($category_id) {
                    $query->where('category_id', $category_id)
                        ->whereHas('tasks', function ($query){
                            $query->where('status', '<>', 'done');
                        });
                }
            ]);
    }

    public function mount()
    {
        $this->tamam = $tamam = [
            'قيادة اللواء' => [
                'officers' => Person::where('unit_id', 1)->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::where('unit_id', 1)->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::where('unit_id', 1)->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'مجموعة 24 صيني' => [
                'officers' => Person::whereIn('unit_id', [15])->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::whereIn('unit_id', [15])->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::whereIn('unit_id', [15])->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'مجموعة 205' => [
                'officers' => Person::whereIn('unit_id', [7,8,9,10,11,12,13,14])->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::whereIn('unit_id', [7,8,9,10,11,12,13,14])->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::whereIn('unit_id', [7,8,9,10,11,12,13,14])->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'مجموعة رمضان' => [
                'officers' => Person::whereIn('unit_id', [16,17,18,19,20,21,22])->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::whereIn('unit_id', [16,17,18,19,20,21,22])->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::whereIn('unit_id', [16,17,18,19,20,21,22])->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'مجموعة سليمان عزت' => [
                'officers' => Person::whereIn('unit_id', [2,3,4,5,6])->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::whereIn('unit_id', [2,3,4,5,6])->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::whereIn('unit_id', [2,3,4,5,6])->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'القاعدة الإدارية' => [
                'officers' => Person::where('unit_id', 27)->where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::where('unit_id', 27)->where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::where('unit_id', 27)->where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count()
            ],
            'إجمالي اللواء' =>[
                'officers' => Person::where('rank_id','<=', 21)->whereIsMission(false)->whereIsForce(true)->count(),
                'subOfficers' => Person::where('rank_id','>', 21)->where('rank_id','<>', 27)->whereIsMission(false)->whereIsForce(true)->count(),
                'soldiers' => Person::where('rank_id','=', 27)->whereIsMission(false)->whereIsForce(true)->count(),
            ],
        ];
    }
}
