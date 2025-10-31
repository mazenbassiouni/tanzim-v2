<?php

namespace App\Filament\Pages;

use App\Models\Speciality;
use App\Models\SpecialityCategory;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class SpecialitiesReport extends Page implements HasTable
{
    use InteractsWithTable, HasPageShield;

    protected static string $view = 'filament.pages.specialities-report';

    protected static ?string $navigationGroup = 'يوميات';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'التخصصات';

    protected static ?string $title = 'يومية التخصصات';

    public function table(Table $table): Table
    {
        $query = Speciality::select(
                        'person_categories.id', 
                        'person_categories.name as الفئة', 
                        'specialities.name as speciality_name',
                        DB::raw('COUNT(CASE WHEN people.rank_id between 22 and 26 THEN 1 END) AS sub_officer'),
                        DB::raw('COUNT(CASE WHEN people.`rank_id` = 27 THEN 1 END) AS soldier'),
                        DB::raw('Count(*) as `all`'),
                    )
                    ->join('person_categories', 'specialities.category_id', '=', 'person_categories.id')
                    ->join('people', 'specialities.id', '=', 'people.speciality_id')
                    ->where('people.is_force', true)    
                    ->where('people.rank_id', '>=', 22)
                    ->groupBy('person_categories.id', 'person_categories.name', 'specialities.name', 'specialities.id')
                    ->orderBy('person_categories.id');

        $table
            ->query($query)
            ->columns([
                TextColumn::make('speciality_name')->label('التخصص'),
                TextColumn::make('sub_officer')->label('راتب عالي')->summarize(Sum::make()),
                TextColumn::make('soldier')->label('مجند')->summarize(Sum::make()),
                TextColumn::make('all')->label('الإجمالي')->summarize(Sum::make()),
            ])
            ->groups([
                'الفئة',
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->defaultGroup('الفئة')
            ->paginated(false);

        return $table;
    }
}
