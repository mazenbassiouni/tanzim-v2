<?php

namespace App\Listeners;

use App\Events\MissionCreated;
use App\Models\Category;
use App\Models\User;
use App\Notifications\FilamentDatabaseNotification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

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
        
                User::first()->notify(new FilamentDatabaseNotification($database_notification->data)); 
            });
        }
    }
}
