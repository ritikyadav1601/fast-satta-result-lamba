<?php

use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__.'/admin.php';
require __DIR__.'/other.php';


Route::get('/',[FrontController::class, 'index'])->name('front.home');
Route::get('/contact',[FrontController::class, 'contact'])->name('front.contact');
Route::get('/disclaimer',[FrontController::class, 'disclaimer'])->name('front.disclaimer');
Route::get('/chart',[FrontController::class, 'chart'])->name('front.chart');
Route::get('/chart/show/{id}',[FrontController::class,'chartShow'])->name('game.show');
Route::get('/extra-chart/show/{id}',[FrontController::class,'extraChartShow'])->name('extra-game.show');
Route::get('/privacy',[FrontController::class,'privacy'])->name('front.privacy');
Route::get('/terms-and-conditions',[FrontController::class,'terms'])->name('front.terms');



Route::get('/blogs',[BlogController::class,'index'])->name('front.blogs');
Route::get('/blog/{slug}',[BlogController::class,'show'])->name('front.single.blog');
