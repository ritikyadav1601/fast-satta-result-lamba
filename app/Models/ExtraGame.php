<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraGame extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gameResult()
    {
        return $this->hasMany(ExtraGameResult::class);
    }

    public function todayResult()
    {
        $today = date('Y-m-d');
        return $this->gameResult()->where('result_date', $today)->first() ?? null;
    }

    public function yesterdayResult()
    {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        return $this->gameResult()->where('result_date', $yesterday)->first() ?? null;
    }
}
