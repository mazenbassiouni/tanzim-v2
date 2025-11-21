<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonFunctionResource\Pages;
use App\Filament\Resources\PersonFunctionResource\RelationManagers;
use App\Models\PersonFunction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonFunctionResource extends Resource
{
    protected static ?string $model = PersonFunction::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = 60;
    
    protected static ?string $navigationLabel = 'الوظائف';

    protected static ?string $modelLabel = 'وظيفة';

    protected static ?string $pluralLabel = 'الوظائف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_officer')
                    ->label('وظيفة ضباط')
                    ->inline(false)
                    ->required()
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePersonFunctions::route('/'),
        ];
    }
}
