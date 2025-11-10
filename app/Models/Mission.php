<?php

namespace App\Models;

use App\Events\MissionCreated;
use App\Events\MissionUpdated;
use App\Models\Scopes\MissionsOfficeScope;
use App\Relations\MergedActivityLogs;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

#[ScopedBy([MissionsOfficeScope::class])]
class Mission extends Model
{
    use HasFactory, LogsActivity;

    protected $casts = [
        'started_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => MissionCreated::class,
        'updated' => MissionUpdated::class,
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

    public function directActivityLog(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function activityLog()
    {
        // newQuery() returns a Builder whose model is ActivityLog
        $instance = new Activity();
        return new MergedActivityLogs($instance->newQuery(), $this);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['category_id', 'office_id', 'title', 'desc', 'started_at'])
            ->logOnlyDirty();
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

    public function addDefaultTasksSilently()
    {
        Task::withoutEvents(function () {
            $this->category->tasks->each(function ($task) {
                $this->tasks()->create([
                    'title' => $task->title,
                    'desc' => $task->desc,
                    'status' => $task->status,
                ]);
            });
        });
    }
}
