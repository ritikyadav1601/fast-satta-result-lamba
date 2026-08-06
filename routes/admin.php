<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\GameResultController;
use App\Http\Controllers\BulkResultController;
use App\Http\Controllers\OtherChartController;
use App\Http\Controllers\Admin\AdminBlogController;


Route::get('admin/login',[AuthController::class, 'login'])->name('admin.login');
Route::post('admin/login/submit',[AuthController::class, 'loginSubmit'])->name('admin.login.submit');

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {

    Route::get('users',[DashboardController::class, 'users'])->name('admin.users');
    Route::post('users',[DashboardController::class, 'userStore'])->name('admin.user.store');
    Route::get('user-edit/{id}',[DashboardController::class, 'userEdit'])->name('admin.user.edit');
    Route::get('user-delete/{id}',[DashboardController::class, 'userDelete'])->name('admin.user.delete');

    Route::get('dashboard',[DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('home-page',[HomeController::class, 'home'])->name('admin.home');
    Route::post('game-store',[HomeController::class, 'gameStore'])->name('games.store');
    Route::get('game-edit/{id}',[HomeController::class, 'gameEdit'])->name('games.edit');
    Route::get('setting',[SettingController::class, 'setting'])->name('admin.setting');
    Route::post('setting/update',[SettingController::class, 'updateSetting'])->name('setting.update');
    Route::get('game/result',[GameResultController::class, 'gameResult'])->name('admin.game.result');
    Route::put('game/result/{id}/update',[GameResultController::class, 'gameResultUpdate'])->name('admin.game.result.update');
    Route::get('question',[HomeController::class, 'question'])->name('admin.question');
    Route::post('question/store',[HomeController::class, 'questionStore'])->name('question.store');
    Route::get('question/edit/{id}',[HomeController::class, 'questionEdit'])->name('admin.question.edit');
    Route::put('question/edit/{id}/update',[HomeController::class, 'questionUpdate'])->name('question.update');

    Route::get('faq',[HomeController::class, 'faq'])->name('admin.faq');
    Route::post('faq/store',[HomeController::class, 'faqStore'])->name('faq.store');
    Route::get('faq/edit/{id}',[HomeController::class, 'faqEdit'])->name('admin.faq.edit');
    Route::put('faq/edit/{id}/update',[HomeController::class, 'faqUpdate'])->name('faq.update');

    Route::get('bulk-result',[BulkResultController::class, 'bulkResult'])->name('admin.bulk.result');
    Route::post('single-result/store',[BulkResultController::class, 'singleResultStore'])->name('games.single.result');
    Route::post('add-result/store',[BulkResultController::class, 'addResultStore'])->name('games.add.result.old');
    Route::post('/games/download/result/old',[BulkResultController::class, 'downloadResultOld'])->name('games.download.result.old');
    Route::get('bulk-result/edit/{id}',[BulkResultController::class, 'bulkResultEdit'])->name('bulk.result.edit');
    Route::put('bulk-result/edit/{id}/update',[BulkResultController::class, 'bulkResultUpdate'])->name('bulk.result.update');
    Route::delete('bulk-result/{id}',[BulkResultController::class, 'bulkResultDelete'])->name('bulk.result.destroy');

    Route::get('other-chart',[OtherChartController::class, 'otherChart'])->name('admin.other.chart');
    Route::post('other-chart/store',[OtherChartController::class, 'otherChartStore'])->name('other.chart.store');
    Route::get('other-chart/edit/{id}',[OtherChartController::class, 'otherChartEdit'])->name('other.chart.edit');
    Route::put('other-chart/edit/{id}/update',[OtherChartController::class, 'otherChartUpdate'])->name('other.chart.update');
    Route::get('other-chart/{id}',[OtherChartController::class, 'otherChartDelete'])->name('other.chart.destroy');


    Route::get('change-password',[AuthController::class, 'changePassword'])->name('admin.change.password');
    Route::post('change-password/submit',[AuthController::class, 'changePasswordSubmit'])->name('admin.change.password.submit');
    Route::get('logout',[AuthController::class, 'logout'])->name('admin.logout');

 Route::get('/blogs',[AdminBlogController::class,'index'])->name('admin.blogs');
    Route::get('/blogs/create',[AdminBlogController::class,'create'])->name('admin.blogs.create');
    Route::post('/blogs/store',[AdminBlogController::class,'store'])->name('admin.blogs.store');
    Route::get('/blogs/edit/{slug}',[AdminBlogController::class,'edit'])->name('admin.blogs.edit');
    Route::put('/blogs/edit/{slug}/update',[AdminBlogController::class,'update'])->name('admin.blogs.update');
    Route::get('/blogs/delete/{slug}',[AdminBlogController::class,'delete'])->name('admin.blogs.delete');

    // Extra Games Routes
    Route::get('extra-game', [\App\Http\Controllers\Admin\ExtraGameController::class, 'index'])->name('admin.extra-game');
    Route::post('extra-game/store', [\App\Http\Controllers\Admin\ExtraGameController::class, 'store'])->name('admin.extra-game.store');
    Route::post('extra-game/csv', [\App\Http\Controllers\Admin\ExtraGameController::class, 'storeCsv'])->name('admin.extra-game.csv');
    Route::get('extra-game/edit/{id}', [\App\Http\Controllers\Admin\ExtraGameController::class, 'edit'])->name('admin.extra-game.edit');

    // Extra Game Results Routes
    Route::get('extra-game-result', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'index'])->name('admin.extra-game-result');
    Route::post('extra-game-result/csv', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'storeCsv'])->name('admin.extra-game-result.csv');
    Route::post('extra-game-result/store', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'storeSingle'])->name('admin.extra-game-result.store');
    Route::get('extra-game-result/edit/{id}', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'edit'])->name('admin.extra-game-result.edit');
    Route::put('extra-game-result/edit/{id}/update', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'update'])->name('admin.extra-game-result.update');
    Route::delete('extra-game-result/{id}', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'destroy'])->name('admin.extra-game-result.destroy');
    Route::post('extra-game-result/sync-today', [\App\Http\Controllers\Admin\ExtraGameResultController::class, 'syncToday'])->name('admin.extra-game-result.sync-today');

});
