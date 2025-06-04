<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelationManagers\PeopleMissionsRelationManager;
use App\Filament\Resources\SubOfficerResource\Pages;
use App\Filament\Resources\SubOfficerResource\RelationManagers;
use App\Models\Person;
use Filament\Forms\Components\DatePicker;
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

class SubOfficerResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'ضباط الصف';

    protected static ?string $modelLabel = 'ضابط صف';

    protected static ?string $pluralLabel = 'ضباط الصف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('military_num')
                    ->label('الرقم العسكرى')
                    ->required(),
                Select::make('rank_id')
                    ->label('الدرجة')
                    ->relationship(name: 'rank',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->whereBetween('id', [22, 26])->orderBy('id'))
                    ->required(),
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                Select::make('speciality_id')
                    ->label('التخصص')
                    ->relationship(name: 'speciality',titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->where('is_officer', false)->orderBy('name'))
                    ->native(false)
                    ->searchable()
                    ->preload()
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
                TextColumn::make('milUnit.name')
                    ->label('التسكين')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit.name')
                    ->label('التسكين الداخلى')
                    ->toggleable(),
                TextColumn::make('speciality.name')
                    ->label('التخصص'),
                TextColumn::make('speciality.category.name')
                    ->label('الفئة')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->label('الدرجة')
                    ->relationship('rank', 'name', fn (Builder $query) => $query->whereBetween('id', [22, 26])->orderBy('id')),
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
            'index' => Pages\ListSubOfficers::route('/'),
            'create' => Pages\CreateSubOfficer::route('/create'),
            'view' => Pages\ViewSubOfficer::route('/{record}'),
            'edit' => Pages\EditSubOfficer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                ->with('rank', 'speciality.category', 'milUnit', 'unit')
                ->whereBetween('rank_id', [22, 26])->orderBy('rank_id')->orderBy('military_num');
    }
}
