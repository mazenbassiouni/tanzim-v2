<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function people(){
        return $this->belongsToMany(Person::class);
    }

    public  function getDueToAttribute()
    {
        return $this->tasks()->where('status', 'active')->whereNotNull('due_to')->orderBy('due_to')->first()?->due_to;
    }
}
