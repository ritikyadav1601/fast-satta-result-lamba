<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
class OtherChart extends Model
{
    use HasFactory;
    protected $fillable = ['khaiwal_name','common_games','whatsapp_numbers'];

}
