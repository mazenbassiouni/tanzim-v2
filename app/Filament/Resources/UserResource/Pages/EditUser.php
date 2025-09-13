<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('resetPassword')
                    ->label('إعادة تعيين كلمة المرور')
                    ->color('warning')
                    ->icon('heroicon-o-key')
                    ->form([
                        TextInput::make('password')
                            ->label('كلمة المرور الجديدة')
                            ->required()
                            ->confirmed()
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('password_confirmation')
                            ->label('تأكيد كلمة المرور الجديدة')
                            ->required()
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->password = bcrypt($data['password']);
                        $record->save();

                        Notification::make()
                            ->title('تم الحفظ')
                            ->success()
                            ->send();
                    }),
            Actions\DeleteAction::make(),
        ];
    }
}
