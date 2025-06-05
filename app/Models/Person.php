<?php

namespace App\Models;

use App\Filament\Resources\OfficerResource;
use App\Filament\Resources\SoldierResource;
use App\Filament\Resources\SubOfficerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Person extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'lay_off_date', 'join_date', 'deleted_date'];

    public function rank(){
        return $this->belongsTo(Rank::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class);
    }

    public function milUnit(){
        return $this->belongsTo(Unit::class, 'mil_unit_id');
    }

    public function getRankNameAttribute()
    {
        return $this->rank->name.'/ '.$this->name;
    }
    
    public function missions(): BelongsToMany
    {
        return $this->belongsToMany(Mission::class);
    }

    public function scopeForce(Builder $query): void
    {
        $query->where('is_force', true);
    }

    public function scopeNotForce(Builder $query): void
    {
        $query->where('is_force', false);
    }

    public function scopeMission(Builder $query): void
    {
        $query->where('is_mission', false);
    }

    public function scopeOfficers(Builder $query): void
    {
        $query->where('rank_id', '<=', 21);
    }

    public function scopeSubOfficers(Builder $query): void
    {
        $query->whereBetween('rank_id', [22, 26]);
    }
    
    public function scopeSoldiers(Builder $query): void
    {
        $query->where('rank_id', 27);
    }

    public function getViewLink()
    {
        if ($this->rank_id <= 21) {
            return OfficerResource::getUrl('view', ['record' => $this->id]);
        } elseif ($this->rank_id <= 26) {
            return SubOfficerResource::getUrl('view', ['record' => $this->id]);
        } elseif ($this->rank_id == 27) {
            return SoldierResource::getUrl('view', ['record' => $this->id]);
        }
    }
}
