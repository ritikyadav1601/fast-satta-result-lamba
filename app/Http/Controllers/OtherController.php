<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\OldResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class OtherController extends Controller
{
    public function gameResult()
    {
         
         $user = auth()->user();

        $today = date('Y-m-d');
        $games = Game::where('status', 1)
            ->with(['gameResult' => function($query) use ($today) {
                $query->whereDate('result_date', $today);
            }])
            ->where('other_chart_id',$user->chart_id)
            ->where('other_chart_id','!=',null)
            ->orderBy('result_time', 'asc')
            ->get();
        return view('other.game.result', compact('games'));
    }

    public function gameResultUpdate(Request $request, $id){
        $user = auth()->user();
        $gameResult = OldResult::where('game_id', $id)->where('result_date', date('Y-m-d'))->first();
        if(!$gameResult){
            // $gameResult = new GameResult();
            // $gameResult->game_id = $id;
            // $gameResult->result = $request->result;
            // $gameResult->result_date = date('Y-m-d');
            // $gameResult->save();

            $oldresult = new OldResult();
            $oldresult->game_id = $id;
            $oldresult->result = $request->result;
            $oldresult->year = date('Y');
            $oldresult->result_date = date('Y-m-d');
            $oldresult->save();
        }
        else{
            $gameResult->result = $request->result;
            $gameResult->save();

            // $oldresult = OldResult::where('game_id', $id)->where('result_date', date('Y-m-d'))->first();
            // $oldresult->result = $request->result;
            // $oldresult->save();
        }
        return redirect()->route('other.game.result')->with('success', 'Game Result updated successfully.');
    }
}
