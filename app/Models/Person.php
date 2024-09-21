<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

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
}
