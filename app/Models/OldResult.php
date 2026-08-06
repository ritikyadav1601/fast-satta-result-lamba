<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldResult extends Model
{
    use HasFactory;

    protected $table = 'old_results';

    protected $fillable = [
        'game_id',
        'result',
        'result_date',
        'year',
    ];
}
