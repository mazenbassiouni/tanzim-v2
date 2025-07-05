<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    const GENERAL = 1;
    const pathological_COUNCIL = 3;
    const TRIAL = 6;
    const CASE_UNDER_INVESTIGATION = 7;
    const INJURY_COUNCIL = 9;
    const INSIDE_ATTACHED = 20;
    const OUTSIDE_ATTACHED = 21;
    const PROMOTION_AND_RENEWAL = 58;
    const OUTSIDE_MISSION = 59;
    const INSIDE_MISSION = 60;
    const SOLDIER_BATCH_LAYOFF = 62;


    const PROMOTION_AND_RENEWAL_EFFECTS = [
        self::pathological_COUNCIL,
        self::TRIAL,
        self::CASE_UNDER_INVESTIGATION,
        self::INJURY_COUNCIL
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(CategoryTasks::class)->orderBy('order');
    }

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }
}
