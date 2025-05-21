<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MissionResource\Pages;
use App\Filament\Resources\MissionResource\RelationManagers;
use App\Models\Mission;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MissionResource extends Resource
{
    protected static ?string $model = Mission::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 10;
    
    protected static ?string $navigationLabel = 'المتابعات';

    protected static ?string $modelLabel = 'متابعة';

    protected static ?string $pluralLabel = 'المتابعات';

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
                TextColumn::make('category.name')
                    ->formatStateUsing(fn (Model $record, string $state): string => $record->category->id != 1 ? $state : $record->title)
                    ->label('النوع')
                    ->tooltip(fn (Model $record): string => $record->desc ?? ''),
                TextColumn::make('people.name')
                    ->label('رتبة أو درجة/ اسم')
                    ->formatStateUsing(fn (Model $record, string $state): string => $record->people->where('name', $state)->first()->rank_name)
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->bulleted(fn (Model $record): bool => $record->people->count() > 1),
                TextColumn::make('due_to')
                    ->label('تاريخ الاستحقاق')
                    ->date('l d/m/Y'),
            ])
            ->recordClasses(function (Model $record) {
                if($record->due_to){
                    if($record->due_to->lt(today())){
                        return 'bg-red-100 dark:bg-red-900';
                    } elseif($record->due_to->eq(today())){
                        return 'bg-yellow-100 dark:bg-yellow-900';
                    } elseif($record->due_to->eq(today()->addDays())){
                        return 'bg-blue-100 dark:bg-blue-900';
                    }
                }
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->modalContent(fn (Model $record): View => view('filament.infolists.components.view-mission', [
                        'mission' => $record
                    ]))
                    ->extraModalFooterActions([
                        Action::make('edit')
                            ->label('تعديل')
                            ->url(fn (Model $record): string => MissionResource::getUrl('custom-edit', ['record' => $record]))
                            ->openUrlInNewTab(),
                    ]),
                Tables\Actions\Action::make('custom-edit')
                    ->iconButton()
                    ->label('تعديل')
                    ->icon('heroicon-s-pencil-square')
                    ->url(fn (Model $record): string => MissionResource::getUrl('custom-edit', ['record' => $record]))
                    ->openUrlInNewTab(),
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
            RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMissions::route('/'),
            // 'create' => Pages\CreateMission::route('/create'),
            // 'view' => Pages\ViewMission::route('/{record}'),
            'custom-edit' => Pages\EditMission::route('/{record}/edit'),
        ];
    }
}
