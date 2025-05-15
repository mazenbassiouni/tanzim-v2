<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'due_to' => 'datetime',
        'done_at' => 'datetime',
    ];

    public function mission(){
        return $this->belongsTo(Mission::class);
    }
}
