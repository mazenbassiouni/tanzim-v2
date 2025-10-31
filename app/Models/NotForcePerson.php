<?php

namespace App\Models;

use App\Filament\Resources\OfficerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class NotForcePerson extends Person
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'lay_off_date', 'join_date', 'deleted_date'];

    protected $table = 'people';

    protected static function booted(): void
    {
        static::addGlobalScope('not_force', function (Builder $builder) {
            $builder->where('is_force', false)->where('is_mission', false);
        });
    }
}
