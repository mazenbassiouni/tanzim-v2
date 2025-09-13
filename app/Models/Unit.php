<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    const KYADA = [1];
    const MAG_24_SINI = [15];
    const MAG_205 = [7,8,9,10,11,12,13,14];
    const MAG_RAMADAN = [16,17,18,19,20,21,22];
    const MAG_FMC = [2,3,4,5,6];
    const KA3DA = [27];
}
