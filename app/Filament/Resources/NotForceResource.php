<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotForceResource\Pages;
use App\Filament\Resources\NotForceResource\RelationManagers;
use App\Models\NotForcePerson;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotForceResource extends Resource
{
    protected static ?string $model = NotForcePerson::class;

    protected static ?string $navigationGroup = 'القوة';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'الشطب';

    protected static ?string $modelLabel = 'شطب';

    protected static ?string $pluralLabel = 'الشطب';

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
                TextColumn::make('military_num')
                    ->label('الرقم العسكرى')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rank.name')
                    ->label('الدرجة'),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('speciality.name')
                    ->label('التخصص')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('milUnit.name')
                    ->label('التسكين')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit.name')
                    ->label('التسكين الداخلى')
                    ->toggleable(),
                TextColumn::make('deleted_date')
                    ->label('تاريخ الشطب')
                    ->date('d-m-Y'),
                TextColumn::make('deleted_desc')
                    ->label('سبب الشطب'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->openUrlInNewTab()
                    ->url(fn ($record) => $record->getViewLink()),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotForces::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->notForce()->mission()->orderBy('deleted_date', 'desc');
    }
}
