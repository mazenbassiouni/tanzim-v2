<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubOfficerResource\Pages;
use App\Filament\Resources\SubOfficerResource\RelationManagers;
use App\Models\Person;
use App\Models\SubOfficer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubOfficerResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?string $navigationLabel = 'ضباط الصف';

    protected static ?string $modelLabel = 'ضابط صف';

    protected static ?string $pluralLabel = 'ضباط الصف';

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
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSubOfficers::route('/'),
            'create' => Pages\CreateSubOfficer::route('/create'),
            'view' => Pages\ViewSubOfficer::route('/{record}'),
            'edit' => Pages\EditSubOfficer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_force', true)->whereBetween('rank_id', [22, 26])->orderBy('rank_id');
    }
}
