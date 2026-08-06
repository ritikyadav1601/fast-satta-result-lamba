<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OldResult;

class Game extends Model
{
    use HasFactory;


    public function gameResult(){
        return $this->hasMany(OldResult::class);}

        public function todayResult(){
            $today = date('Y-m-d');
            return $this->gameResult()->where('result_date', $today)->first() ?? null;
        }

        public function yesterdayResult(){
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            return $this->gameResult()->where('result_date', $yesterday)->first() ?? null;
        }
}
