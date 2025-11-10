<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Task;

class MergedActivityLogs extends Relation
{
    /**
     * Mapping of task_id => mission_id used when eager loading to match results.
     *
     * @var array
     */
    protected $taskToMission = [];

    /**
     * Construct the relation.
     *
     * $query should be Activity::query() (or instance->newQuery()).
     * $parent should be the Mission model instance (or when used for eager loading, the "parent" model prototype).
     */
    public function __construct(Builder $query, Model $parent)
    {
        parent::__construct($query, $parent);
    }

    /**
     * Add constraints for lazy loading (single parent).
     */
    public function addConstraints()
    {
        if (static::$constraints) {
            $mission = $this->getParent();
            $taskIds = $mission->tasks()->pluck('id')->all();

            $this->query->where(function ($q) use ($mission, $taskIds) {
                $q->where(function ($sub) use ($mission) {
                    $sub->where('subject_type', get_class($mission))
                        ->where('subject_id', $mission->getKey());
                });

                if (! empty($taskIds)) {
                    $q->orWhere(function ($sub) use ($taskIds) {
                        $sub->where('subject_type', Task::class)
                            ->whereIn('subject_id', $taskIds);
                    });
                }
            });
        }
    }

    /**
     * Add constraints for eager loading (multiple parents).
     *
     * Here we constrain the underlying query so it returns all activity logs
     * that belong directly to any of the provided missions OR to any of their tasks.
     *
     * @param  array  $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $missionIds = array_values(array_filter(array_map(fn($m) => $m->getKey(), $models)));

        if (empty($missionIds)) {
            // nothing to constrain
            $this->query->whereRaw('0 = 1');
            return;
        }

        // fetch tasks for all missions in a single query
        $tasks = Task::whereIn('mission_id', $missionIds)->get(['id', 'mission_id']);

        $taskIds = $tasks->pluck('id')->all();

        // keep mapping task_id => mission_id for match()
        $this->taskToMission = $tasks->pluck('mission_id', 'id')->all();

        $this->query->where(function ($q) use ($missionIds, $taskIds) {
            $q->where(function ($sub) use ($missionIds) {
                $sub->where('subject_type', $this->parent->getMorphClass() ?: get_class($this->parent))
                    ->whereIn('subject_id', $missionIds);
            });

            if (! empty($taskIds)) {
                $q->orWhere(function ($sub) use ($taskIds) {
                    $sub->where('subject_type', Task::class)
                        ->whereIn('subject_id', $taskIds);
                });
            }
        });
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array   $models
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array      $models
     * @param  Collection $results
     * @param  string     $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        // group results by parent mission id
        $grouped = [];

        foreach ($results as $result) {
            // if the log is directly on a mission
            if ($result->subject_type === ($this->parent->getMorphClass() ?: get_class($this->parent))) {
                $missionId = $result->subject_id;
            } elseif ($result->subject_type === Task::class) {
                // if on a Task, find mission via mapping we built in addEagerConstraints()
                $taskId = $result->subject_id;
                $missionId = $this->taskToMission[$taskId] ?? null;
            } else {
                $missionId = null;
            }

            if ($missionId !== null) {
                $grouped[$missionId][] = $result;
            }
        }

        // assign collections to each model
        foreach ($models as $model) {
            $items = $grouped[$model->getKey()] ?? [];
            $model->setRelation($relation, $this->related->newCollection($items));
        }

        return $models;
    }

    /**
     * Get the results for the relation (lazy).
     *
     * @return Collection
     */
    public function getResults()
    {
        return $this->query->get();
    }

    /**
     * Override to return the underlying query builder
     */
    public function getQuery()
    {
        return $this->query;
    }
}
