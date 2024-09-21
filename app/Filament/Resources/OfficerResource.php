<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficerResource\Pages;
use App\Filament\Resources\OfficerResource\RelationManagers;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfficerResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?string $navigationLabel = 'الضباط';

    protected static ?string $modelLabel = 'فرد';

    protected static ?string $pluralLabel = 'الضباط';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rank.name')->label('الرتبة'),
                TextColumn::make('name')->label('الاسم'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
        return parent::getEloquentQuery()->where('is_force', true)->where('rank_id', '<=', 21)->orderBy('rank_id');
    }
}
