<?php

namespace App\Models;

use App\Filament\Resources\SubOfficerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class SubOfficer extends Person
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'lay_off_date', 'join_date', 'deleted_date'];

    protected $table = 'people';

    protected static function booted(): void
    {
        static::addGlobalScope('officers', function (Builder $builder) {
            $builder->whereBetween('rank_id', [22, 26]);
        });
    }

    public function getViewLink(): string
    {
        return SubOfficerResource::getUrl('view', ['record' => $this->id]);
    }
}
