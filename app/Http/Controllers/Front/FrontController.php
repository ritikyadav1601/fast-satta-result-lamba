<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Game;
use App\Models\QuestionAnswer;
use App\Models\FAQ;
use App\Models\OldResult;
use App\Models\GameResult;
use App\Models\OtherChart;

class FrontController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        // Load settings, questions, and FAQs
        $settings = Setting::pluck('value', 'key')->toArray();
        $qa = QuestionAnswer::get();
        $faq = FAQ::get();

        // Load games and results with eager loading
        $games = Game::where('status', 1)
            ->with(['gameResult' => function ($query) use ($today, $yesterday) {
                $query->whereIn('result_date', [$today, $yesterday]);
            }])
            ->orderBy('time', 'asc')
            ->get();
        $gamess = $games->groupBy('other_chart_id');


        $otherChart = OtherChart::get();

        // Attach today and yesterday's results to each game
        foreach ($games as $game) {
            $game->today_result = $game->gameResult->firstWhere('result_date', $today)->result ?? null;
            $game->yesterday_result = $game->gameResult->firstWhere('result_date', $yesterday)->result ?? null;
        }

        // Prepare yearly results for the current month
        $currentYear = date('Y');
        $currentMonth = date('m');
        $this_year_results = OldResult::whereMonth('result_date', $currentMonth)
            ->get()
            ->groupBy(['result_date', 'game_id']);

        // Generate dates for the current month
        $dates = collect(range(1, cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear)))
            ->map(fn($day) => sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day));

        $extraGames = \App\Models\ExtraGame::where('status', 1)
            ->with(['gameResult' => function ($query) use ($today, $yesterday) {
                $query->whereIn('result_date', [$today, $yesterday]);
            }])
            ->orderBy('time', 'asc')
            ->get();
            
        foreach ($extraGames as $game) {
            $game->today_result = $game->gameResult->firstWhere('result_date', $today)->result ?? null;
            $game->yesterday_result = $game->gameResult->firstWhere('result_date', $yesterday)->result ?? null;
        }

        return view('front.index', compact('settings', 'gamess','games', 'extraGames', 'qa', 'faq', 'this_year_results', 'dates', 'currentYear', 'otherChart'));
    }

    public function contact()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.contact.index', compact('settings'));
    }

    public function disclaimer()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('front.disclaimer.index', compact('settings'));
    }

    public function chart()
    {
        $games = Game::where('status', 1)->get();
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.chart.index', compact('settings', 'games'));
    }

    public function chartShow($id){
        $game = Game::find($id);
        $year = request()->year;
        if(!$year){
            $gameResult = OldResult::where('game_id', $id)->where('year', date('Y'))->orderBy('result_date', 'asc')->get();
        }else{
            $gameResult = OldResult::where('game_id', $id)->where('year', $year)->orderBy('result_date', 'asc')->get();
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.chart.show', compact('game', 'gameResult', 'settings'));
    }

    public function extraChartShow($id){
        $game = \App\Models\ExtraGame::find($id);
        $year = request()->year;
        if(!$year){
            $gameResult = \App\Models\ExtraGameResult::where('extra_game_id', $id)->where('year', date('Y'))->orderBy('result_date', 'asc')->get();
        }else{
            $gameResult = \App\Models\ExtraGameResult::where('extra_game_id', $id)->where('year', $year)->orderBy('result_date', 'asc')->get();
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.chart.extra-show', compact('game', 'gameResult', 'settings'));
    }

    public function terms(){
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.pages.terms', compact('settings'));
    }

    public function privacy(){
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('front.pages.privacy', compact('settings'));
    }
}
