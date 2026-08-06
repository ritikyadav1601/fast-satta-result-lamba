<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\QuestionAnswer;
use App\Models\FAQ;
use App\Models\OtherChart;

class HomeController extends Controller
{

    public function home()
    {
        $games = Game::where('status', 1)->get();
        $otherChart = OtherChart::get();
        return view('admin.Home.index', compact('games','otherChart'));
    }

    public function gameStore(Request $request){

        $request->validate([
            'name' => 'required',
            'english_name' => 'required',
            'time' => 'required',
            'result_time' => 'required',
        ]);
        if($request->id){
            $game = Game::find($request->id);
        }else{
            $game = new Game();
        }
        $game->name = $request->name;
        $game->time =  $request->time;
        $game->english_name = $request->english_name;
        $game->result_time = $request->result_time;
        $game->slug = str_replace(' ', '-', strtolower($request->english_name));
        $game->status = 1;
        $game->other_chart_id = $request->other_chart_id ?? null;
        $game->save();

        return redirect()->route('admin.home')->with('success', 'Game created successfully.');
    }

    public function question(){
        $question = QuestionAnswer::get();
        return view('admin.qa.index', compact('question'));
    }

    public function questionStore(Request $request){

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $question = new QuestionAnswer();
        $question->question = $request->question;
        $question->answer =  $request->answer;
        $question->status = 1;
        $question->save();

        return redirect()->route('admin.question')->with('success', 'Question created successfully.');
    }

    public function questionEdit($id){
        $question = QuestionAnswer::find($id);
        return view('admin.qa.edit', compact('question'));
    }

    public function questionUpdate(Request $request, $id){
        $question = QuestionAnswer::find($id);
        $question->question = $request->question;
        $question->answer =  $request->answer;
        $question->save();
        return redirect()->route('admin.question')->with('success', 'Question updated successfully.');
    }


    public function faq(){
        $question = FAQ::get();
        return view('admin.qa.faq', compact('question'));
    }

    public function faqStore(Request $request){

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $question = new FAQ();
        $question->question = $request->question;
        $question->answer =  $request->answer;
        $question->save();

        return redirect()->back()->with('success', 'Question created successfully.');
    }

    public function faqEdit($id){
        $question = FAQ::find($id);
        return view('admin.qa.edit', compact('question'));
    }

    public function faqUpdate(Request $request, $id){
        $question = FAQ::find($id);
        $question->question = $request->question;
        $question->answer =  $request->answer;
        $question->save();
        return redirect()->route('admin.question')->with('success', 'Question updated successfully.');
    }

    public function gameEdit($id){
        $game = Game::find($id);
        return view('admin.Home.edit-game', compact('game'));
    }


}
