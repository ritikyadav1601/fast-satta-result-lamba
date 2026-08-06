<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    use HasFactory;


    public function game()
    {
        return $this->belongsTo(Game::class);
    }

     function todayResult()
    {
        $today = date('Y-m-d');
        return $this->whereDate('created_at', $today)->first()->result;
    }
}
