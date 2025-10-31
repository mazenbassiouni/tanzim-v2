<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speciality extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(SpecialityCategory::class, 'category_id');
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class)
            ->using(SpecialityUnit::class)
            ->withPivot(['peace_count', 'war_count']);
    }
}
