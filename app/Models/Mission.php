<?php

namespace App\Models;

use App\Models\Scopes\MissionsOfficeScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([MissionsOfficeScope::class])]
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

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function scopeDone(Builder $query): void
    {
        $query->whereDoesntHave('tasks', function (Builder $query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'active');
        });
    }

    public function scopeActive(Builder $query): void
    {
        $query->whereRelation('tasks', 'status', 'active');
    }

    public function scopePending(Builder $query): void
    {
        $query->whereRelation('tasks', 'status', 'pending')
            ->whereDoesntHave('tasks', function (Builder $query) {
                $query->where('status', 'active');
            });
    }

    public  function getDueToAttribute()
    {
        return $this->tasks()->where('status', 'active')->whereNotNull('due_to')->orderBy('due_to')->first()?->due_to;
    }

    public function getDisplayTitleAttribute()
    {
        if ($this->category_id == Category::GENERAL) {
            return $this->title;
        } else {
            return $this->category?->name;
        }
    }
    
    public static function startedAtLabel(int|null $category_id)
    {
        if ($category_id == Category::SOLDIER_BATCH_LAYOFF) {
            return 'تاريخ التسريح';
        } else {
            return 'تاريخ البدء';
        }
    }

    public function getStartedAtLabel()
    {
        return self::startedAtLabel($this->category_id);
    }
}
