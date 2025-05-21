<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficerResource\Pages;
use App\Filament\Resources\OfficerResource\RelationManagers;
use App\Models\Person;
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

class OfficerResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'الضباط';

    protected static ?string $modelLabel = 'ضابط';

    protected static ?string $pluralLabel = 'الضباط';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('military_num')
                    ->label('الرقم العسكرى')
                    ->required(),
                TextInput::make('seniority_num')
                    ->label('الرقم الاقدمية')
                    ->required(),
                Select::make('rank_id')
                    ->label('الرتبة')
                    ->relationship(name: 'rank',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('id', '<=', 21)->orderBy('id'))
                    ->required(),
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
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
                Select::make('speciality_id')
                    ->label('التخصص')
                    ->relationship(name: 'speciality',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('is_officer', true)->orderBy('name'))
                    ->native(false)
                    ->searchable()
                    ->preload()
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
                DatePicker::make('delete_date')
                    ->label('تاريخ الشطب')
                    ->required(fn (Get $get) => !$get('is_force') && !$get('is_mission'))
                    ->hidden(fn (Get $get) => $get('is_force') || $get('is_mission')),
                TextInput::make('delete_desc')
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
                    ->label('الرتبة')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('milUnit.name')
                    ->label('التسكين')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit.name')
                    ->label('التسكين الداخلى')
                    ->toggleable(),
                TextColumn::make('speciality.name')
                    ->label('التخصص')
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_force')
                    ->label('قوة')
                    ->trueLabel('نعم')
                    ->falseLabel('لا')
                    ->placeholder('الكل')
                    ->default(true),
                SelectFilter::make('rank')
                    ->multiple()
                    ->preload()
                    ->label('الرتبة')
                    ->relationship('rank', 'name', fn (Builder $query) => $query->where('id', '<=', 21)->orderBy('id')),
                SelectFilter::make('speciality')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('التخصص')
                    ->relationship('speciality', 'name', fn (Builder $query) => $query->whereIsOfficer(true)),
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
                    //
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOfficers::route('/'),
            'create' => Pages\CreateOfficer::route('/create'),
            'view' => Pages\ViewOfficer::route('/{record}'),
            'edit' => Pages\EditOfficer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                ->with('rank', 'speciality.category', 'milUnit', 'unit')
                ->where('is_force', true)->where('rank_id', '<=', 21)->orderBy('rank_id');
    }
}
