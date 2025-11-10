<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\MissionCreated;
use App\Filament\Resources\MissionResource;
use App\Models\Category;
use App\Models\User;
use App\Notifications\FilamentDatabaseNotification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class SendMissionNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MissionCreated $event): void
    {
        $mission = $event->mission;

        if( in_array($mission->category_id, Category::PROMOTION_AND_RENEWAL_EFFECTS) ){

            $people = $mission->people()->whereHas('missions', function ($query) {
                $query->where('category_id', Category::PROMOTION_AND_RENEWAL)
                    ->where(function ($query) {
                        $query->active()
                            ->orWhere(fn ($query) => $query->pending());
                    });
            });

            $people->each(function ($person) use ($mission) {
               /** @var \Filament\Notifications\DatabaseNotification $database_notification */
                $database_notification = Notification::make()
                    ->title($person->rankName.' لديه '.$mission->category->name.' و هو منتظر ترقي و تجديد')    
                    ->warning()
                    ->danger()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->actions([
                        Action::make('view')
                            ->label('')
                            ->icon('heroicon-o-eye')
                            ->markAsRead(),
    
                        Action::make('unview')
                            ->label('')
                            ->icon('heroicon-o-eye-slash')
                            ->markAsUnread(),
    
                        Action::make('viewPerson')
                            ->label('عرض الفرد')
                            ->url($person->getViewLink()),
                    ])
                    ->toDatabase();
        
                User::role([RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN->value])->get()->each(function (User $user) use ($database_notification) {
                    $user->notify(new FilamentDatabaseNotification($database_notification->data));
                });
            });
        }

        // DB::afterCommit(function () use ($mission) {
        //     dd($mission->refresh()->load('people', 'directActivityLog'));
        // });
        
        // $title = 'تم إنشاء متابعة ('.$mission->category->name.') جديدة';
        
        // $peopleCount = $mission->people()->count();

        // if ($peopleCount === 1) {
        //     $title .= ' خاصة '.$mission->people()->first()->rankName;
        // }elseif ($peopleCount > 1) {
        //     $title .= ' خاصة '.$mission->people()->first()->rankName.' و '.($peopleCount - 1).' آخرين';
        // }
        
        // $database_notification = Notification::make()
        //     ->title($title)
        //     ->warning()
        //     ->danger()
        //     ->icon('heroicon-o-exclamation-triangle')
        //     ->actions([
        //         Action::make('view')
        //             ->label('')
        //             ->icon('heroicon-o-eye')
        //             ->markAsRead(),

        //         Action::make('unview')
        //             ->label('')
        //             ->icon('heroicon-o-eye-slash')
        //             ->markAsUnread(),

        //         Action::make('viewPerson')
        //             ->label('عرض المتابعة')
        //             ->url(MissionResource::getUrl('view', ['record' => $mission->id])),
        //     ])
        //     ->toDatabase();

        // // $creationLog = $mission->directActivityLog()->where('description', 'created')->with('causer')->first();

        // User::role([RoleEnum::SUPER_ADMIN->value, RoleEnum::ADMIN->value])->get()->each(function (User $user) use ($database_notification) {
        //     $user->notify(new FilamentDatabaseNotification($database_notification->data));
        // });
        
    }
}
