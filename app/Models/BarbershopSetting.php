<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbershopSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'opening_time',
        'closing_time',
    ];
}
