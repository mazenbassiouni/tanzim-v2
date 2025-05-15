<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryTasks extends Model
{
    use HasFactory;

    public function tasks(): HasMany
    {
        return $this->hasMany(CategoryTasks::class)->orderBy('order');
    }
}
