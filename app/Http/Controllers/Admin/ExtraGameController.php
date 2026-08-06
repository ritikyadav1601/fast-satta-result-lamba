<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraGame;

class ExtraGameController extends Controller
{
    public function index()
    {
        $games = ExtraGame::get();
        return view('admin.extra-game.index', compact('games'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'english_name' => 'required',
            'time' => 'required',
            'result_time' => 'required',
        ]);
        
        if($request->id){
            $game = ExtraGame::find($request->id);
        }else{
            $game = new ExtraGame();
        }
        $game->name = $request->name;
        $game->time =  $request->time;
        $game->english_name = $request->english_name;
        $game->result_time = $request->result_time;
        $game->slug = str_replace(' ', '-', strtolower($request->english_name));
        $game->status = 1;
        $game->save();

        return redirect()->route('admin.extra-game')->with('success', 'Extra Game created successfully.');
    }

    public function edit($id){
        $game = ExtraGame::find($id);
        return view('admin.extra-game.edit', compact('game'));
    }

    public function storeCsv(Request $request) 
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $csvFile = $request->file('csv_file');
    
        if ($request->hasFile('csv_file')) {
            $path = $csvFile->getRealPath();
            $csv = \League\Csv\Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
    
            foreach ($csv as $row) {
                ExtraGame::create([
                    'name'         => $row['name'],
                    'english_name' => $row['english_name'],
                    'time'         => $row['time'],
                    'result_time'  => $row['result_time'],
                    'slug'         => str_replace(' ', '-', strtolower($row['english_name'])),
                    'status'       => 1,
                ]);
            }
            return redirect()->back()->with('success', 'Extra Games CSV imported successfully.');
        }
    
        return redirect()->back()->with('error', 'Please upload a CSV file.');
    }
}
