<?php

namespace App\Filament\Pages;
use App\Filament\Widgets\ForceOverview;
use App\Models\Officer;
use App\Models\Person;
use App\Models\Soldier;
use App\Models\SubOfficer;
use App\Models\Unit;
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

    public function peopleQuery(int $category_id, bool $is_force = true)
    {
        return Person::where('is_force', $is_force)->whereHas('missions', function ($query) use ($category_id) {
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
                'officers' => Officer::whereIn('unit_id', Unit::KYADA)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::KYADA)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::KYADA)->force()->count()
            ],
            'مجموعة 24 صيني' => [
                'officers' => Officer::whereIn('unit_id', Unit::MAG_24_SINI)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::MAG_24_SINI)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::MAG_24_SINI)->force()->count()
            ],
            'مجموعة 205' => [
                'officers' => Officer::whereIn('unit_id', Unit::MAG_205)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::MAG_205)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::MAG_205)->force()->count()
            ],
            'مجموعة رمضان' => [
                'officers' => Officer::whereIn('unit_id', Unit::MAG_RAMADAN)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::MAG_RAMADAN)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::MAG_RAMADAN)->force()->count()
            ],
            'مجموعة سليمان عزت' => [
                'officers' => Officer::whereIn('unit_id', Unit::MAG_FMC)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::MAG_FMC)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::MAG_FMC)->force()->count()
            ],
            'القاعدة الإدارية' => [
                'officers' => Officer::whereIn('unit_id', Unit::KA3DA)->force()->count(),
                'subOfficers' => SubOfficer::whereIn('unit_id', Unit::KA3DA)->force()->count(),
                'soldiers' => Soldier::whereIn('unit_id', Unit::KA3DA)->force()->count()
            ],
            'إجمالي اللواء' =>[
                'officers' => Officer::force()->count(),
                'subOfficers' => SubOfficer::force()->count(),
                'soldiers' => Soldier::force()->count(),
            ],
        ];
    }
}
