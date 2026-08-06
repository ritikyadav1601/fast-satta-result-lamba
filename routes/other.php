<?php

use App\Http\Controllers\Admin\GameResultController;
use App\Http\Controllers\OtherController;
use Illuminate\Support\Facades\Route;

Route::get('game/result',[OtherController::class, 'gameResult'])->name('other.game.result');
Route::put('game/result/{id}/update',[OtherController::class, 'gameResultUpdate'])->name('other.game.result.update');
