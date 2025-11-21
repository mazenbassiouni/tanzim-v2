<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelationManagers\PeopleMissionsRelationManager;
use App\Filament\Resources\SoldierResource\Pages;
use App\Filament\Resources\SoldierResource\RelationManagers;
use App\Models\Soldier;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoldierResource extends Resource
{
    protected static ?string $model = Soldier::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'الجنود';

    protected static ?string $modelLabel = 'جندى';

    protected static ?string $pluralLabel = 'الجنود';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('military_num')
                    ->label('الرقم العسكرى')
                    ->required(),
                Select::make('rank_id')
                    ->label('الدرجة')
                    ->native(false)
                    ->disabled()
                    ->default(27)
                    ->relationship(name: 'rank',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('id', 27))
                    ->required(),
                TextInput::make('name')
                    ->label('الاسم')
                    ->columnSpanFull()
                    ->required(),
                Select::make('speciality_id')
                    ->label('التخصص')
                    ->relationship(name: 'speciality',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('is_officer', false)->orderBy('name'))
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get) => !$get('is_mission') || $get('is_force'))
                    ->hidden(fn (Get $get) => !$get('is_force') && $get('is_mission')),
                Select::make('person_function_id')
                    ->label('الوظيفة')
                    ->relationship(name: 'personFunction',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('is_officer', false)->orderBy('name'))
                    ->native(false)
                    ->searchable()
                    ->required(fn (Get $get) => !$get('is_mission') || $get('is_force'))
                    ->hidden(fn (Get $get) => !$get('is_force') && $get('is_mission')),
                Select::make('mil_unit_id')
                    ->label('التسكين')
                    ->relationship(name: 'milUnit',titleAttribute: 'name')
                    ->native(false)
                    ->required(fn (Get $get) => !$get('is_mission') || $get('is_force'))
                    ->hidden(fn (Get $get) => !$get('is_force') && $get('is_mission')),
                Select::make('unit_id')
                    ->label('التسكين الداخلى')
                    ->relationship(name: 'unit',titleAttribute: 'name')
                    ->native(false)
                    ->required(),  
                DatePicker::make('join_date')
                    ->label('تاريخ الضم')
                    ->required(fn (Get $get) => !$get('is_mission') || $get('is_force'))
                    ->hidden(fn (Get $get) => !$get('is_force') && $get('is_mission')),
                TextInput::make('join_desc')
                    ->label('ملاحظات الضم')
                    ->hidden(fn (Get $get) => !$get('is_force') && $get('is_mission')),
                Select::make('medical_state')
                    ->default(1)
                    ->options([
                        1 => 'لائق',
                        0 => 'غير لائق',
                        2 => 'لائق للمستوى الادنى',
                    ])
                    ->native(false)
                    ->label('الموقف الطبي')
                    ->required(fn (Get $get) => !$get('is_mission'))
                    ->hidden(fn (Get $get) => $get('is_mission')),
                TextInput::make('medical_cause')
                    ->label('ملاحظات الموقف الطبي')
                    ->hidden(fn (Get $get) => $get('is_mission')),
                DatePicker::make('lay_off_date')
                    ->label('تاريخ التسريح')
                    ->required(),
                Grid::make(2)
                    ->schema([
                        Toggle::make('is_force')
                            ->label('قوة')
                            ->inline(false)
                            ->default(true)
                            ->live(),
                        Toggle::make('is_mission')
                            ->label('مأمورية / الحاق')
                            ->inline(false)
                            ->default(false)
                            ->live()
                            ->hidden(fn (Get $get) => $get('is_force')),
                    ]),
                DatePicker::make('deleted_date')
                    ->label('تاريخ الشطب')
                    ->required(fn (Get $get) => !$get('is_force') && !$get('is_mission'))
                    ->hidden(fn (Get $get) => $get('is_force') || $get('is_mission')),
                TextInput::make('deleted_desc')
                    ->label('ملاحظات الشطب')
                    ->required(fn (Get $get) => !$get('is_force') && !$get('is_mission'))
                    ->hidden(fn (Get $get) => $get('is_force') || $get('is_mission')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('military_num')
                    ->label('الرقم العسكرى')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rank.name')
                    ->label('الدرجة'),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('lay_off_date')
                    ->label('تاريخ التسريح')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('milUnit.name')
                    ->label('التسكين')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit.name')
                    ->label('التسكين الداخلى')
                    ->toggleable(),
                TextColumn::make('speciality.name')
                    ->label('التخصص')
                    ->toggleable(),
                TextColumn::make('personFunction.name')
                    ->label('الوظيفة')
                    ->toggleable(),
                TextColumn::make('speciality.category.name')
                    ->label('الفئة')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('lay_off_date', 'asc')
            ->filters([
                TernaryFilter::make('is_force')
                    ->label('قوة')
                    ->trueLabel('نعم')
                    ->falseLabel('لا')
                    ->placeholder('الكل')
                    ->default(true),
                SelectFilter::make('category')
                    ->multiple()
                    ->preload()
                    ->label('الفئة')
                    ->relationship('speciality.category', 'name', fn (Builder $query) => $query->orderBy('id')),
                SelectFilter::make('speciality')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('التخصص')
                    ->relationship('speciality', 'name', fn (Builder $query) => $query->whereIsOfficer(false)),
                SelectFilter::make('milUnit')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('التسكين')
                    ->relationship('milUnit', 'name'),
                SelectFilter::make('unit')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('التسكين الداخلى')
                    ->relationship('unit', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PeopleMissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoldiers::route('/'),
            'create' => Pages\CreateSoldier::route('/create'),
            'view' => Pages\ViewSoldier::route('/{record}'),
            'edit' => Pages\EditSoldier::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                ->with('rank', 'speciality.category', 'milUnit', 'unit');
    }
}
