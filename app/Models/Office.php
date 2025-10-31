<?php

namespace App\Models;

use App\Models\Scopes\OfficesOfficeScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy([OfficesOfficeScope::class])]
class Office extends Model
{
    use HasFactory;

    const EDARA = 2;
    const OFFICER_AFFAIRES = 3;
    const SEGELAT = 4;
    const PERSONAL_AFFAIRES = 5;
}
