<?php

namespace App\Models;

use App\Filament\Resources\OfficerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Officer extends Person
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'lay_off_date', 'join_date', 'deleted_date'];

    protected $table = 'people';

    protected static function booted(): void
    {
        static::addGlobalScope('officers', function (Builder $builder) {
            $builder->where('rank_id', '<=', 21);
        });
    }

    public function getViewLink(): string
    {
        return OfficerResource::getUrl('view', ['record' => $this->id]);
    }
}
