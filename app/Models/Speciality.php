<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(SpecialityCategory::class, 'category_id');
    }
}
