<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraGameResult extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function extraGame()
    {
        return $this->belongsTo(ExtraGame::class);
    }
}
