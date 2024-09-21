<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoliderResource\Pages;
use App\Filament\Resources\SoliderResource\RelationManagers;
use App\Models\Person;
use App\Models\Solider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoliderResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?string $navigationLabel = 'الجنود';

    protected static ?string $modelLabel = 'جندى';

    protected static ?string $pluralLabel = 'الجنود';

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
            'index' => Pages\ListSoliders::route('/'),
            'create' => Pages\CreateSolider::route('/create'),
            'view' => Pages\ViewSolider::route('/{record}'),
            'edit' => Pages\EditSolider::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_force', true)->where('rank_id',27)->orderBy('rank_id');
    }
}
