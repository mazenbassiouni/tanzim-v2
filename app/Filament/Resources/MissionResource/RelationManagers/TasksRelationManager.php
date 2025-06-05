<?php

namespace App\Filament\Resources\MissionResource\RelationManagers;

use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'البنود';

    protected static ?string $modelLabel = 'بند';

    protected static ?string $pluralModelLabel = 'البنود';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('desc')
                    ->label('الموضوع')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'جاري',
                        'pending' => 'معلق',
                        'done' => 'منتهي',
                    ])
                    ->native(false)
                    ->live()
                    ->required(),
                DatePicker::make('due_to')
                    ->label('تاريخ الاستحقاق')
                    ->required()
                    ->visible(fn (Get $get): bool => $get('status') === 'active'),
                DatePicker::make('done_at')
                    ->label('تاريخ الانتهاء')
                    ->required()
                    ->visible(fn (Get $get): bool => $get('status') === 'done'),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->tooltip(fn ($record): string => $record->desc ?? '')
                    ->label('العنوان'),
                TextColumn::make('id')
                    ->label('')
                    ->formatStateUsing(function (Task $record) {
                        switch ($record->status) {
                            case 'done':
                                return $record->done_at?->translatedFormat('l d/m/Y') ?? '';
                            case 'active':
                                return $record->due_to?->translatedFormat('l d/m/Y') ?? '';
                            default:
                                return '';
                        }
                    })
            ])
            ->recordClasses(function (Task $record) {
                switch ($record->status) {
                    case 'done':
                        return 'bg-green-100 dark:bg-green-900';
                    case 'active':
                        return 'bg-red-100 dark:bg-red-900';
                    default:
                        return 'bg-yellow-100 dark:bg-yellow-900';
                }
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('end')
                    ->label('إنتهاء البند')
                    ->iconButton()
                    ->icon('heroicon-s-check')
                    ->color('success')
                    ->form([
                        DatePicker::make('done_at')
                            ->label('تاريخ الانتهاء')   
                            ->required()
                            ->default(today()),
                    ])
                    ->action(function (Task $record, array $data): void {
                        $record->update([
                            'status' => 'done',
                            'done_at' => $data['done_at'],
                        ]);

                        Notification::make()
                            ->title('تم إنتهاء البند')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn (Task $record): bool => $record->status === 'done'),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption('all');
    }
}
