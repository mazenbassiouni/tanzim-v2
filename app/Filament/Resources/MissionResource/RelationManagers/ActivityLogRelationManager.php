<?php

namespace App\Filament\Resources\MissionResource\RelationManagers;

use App\Enums\RoleEnum;
use App\Models\Mission;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\View;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogRelationManager extends RelationManager
{
    protected static string $relationship = 'activityLog';

    protected static ?string $title = 'سجل النشاط';

    protected static ?string $modelLabel = 'سجل النشاط';

    protected static ?string $pluralModelLabel = 'سجلات النشاط';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('subject')
                    ->label('البند')
                    ->columnSpanFull()
                    ->weight('bold')
                    ->color('primary')
                    ->formatStateUsing(function ($state) {
                        if ($state instanceof Mission) {
                            return '';
                        }
                        return $state->title;
                    }),
                TextEntry::make('description')
                    ->label('نوع النشاط')
                    ->weight('bold')
                    ->color('primary')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'إنشاء',
                        'updated' => 'تحديث',
                        'deleted' => 'حذف',
                        default => $state,
                    }),
                TextEntry::make('causer.name')
                    ->label('بواسطة')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('created_at')
                    ->label('التاريخ')
                    ->weight('bold')
                    ->color('primary')
                    ->dateTime('l d/m/Y h:i A'),
                View::make('properties')
                    ->view('filament.infolists.components.activity-log-details')
                    ->columnSpanFull(),

            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('البند')
                    ->limit(50)
                    ->formatStateUsing(function ($state, Model $record) {
                        if ($record->subject_type === Mission::class) {
                            return '';
                        }
                        
                        return $state->title;
                    }),
                TextColumn::make('description')
                    ->label('نوع النشاط')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'إنشاء',
                        'updated' => 'تحديث',
                        'deleted' => 'حذف',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'updated' => 'warning',
                        'created' => 'success',
                        'deleted' => 'danger',
                    }),
                TextColumn::make('causer.name')
                    ->label('بواسطة'),
                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('l d/m/Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return Auth::user()->hasRole([RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN->value]);
    }
}
