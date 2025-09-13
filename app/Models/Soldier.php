<?php

namespace App\Models;

use App\Filament\Resources\SoldierResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Soldier extends Person
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'lay_off_date', 'join_date', 'deleted_date'];

    protected $table = 'people';

    protected static function booted(): void
    {
        static::addGlobalScope('officers', function (Builder $builder) {
            $builder->where('rank_id', 27);
        });
    }

    public function getViewLink(): string
    {
        return SoldierResource::getUrl('view', ['record' => $this->id]);
    }
}
