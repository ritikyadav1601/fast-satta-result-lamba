<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OldResult;
use League\Csv\Reader;

class BulkResultController extends Controller
{
    public function bulkResult(Request $request)
    {
        $date = $request->query('date');
        $games = Game::all();
        $oldResult = OldResult::orderBy('result_date', 'desc')->paginate(10);
        if($date){
            $oldResult = OldResult::where('result_date', $date)->orderBy('result_date', 'desc')->paginate(10);
        }
        foreach($oldResult as $result){
            $game = $games->where('id', $result->game_id)->first();
            $result->game_name = $game->name;
        }
        return view('admin.result.bulk-result', compact('games', 'oldResult'));
    }

    public function singleResultStore(Request $request) 
    {
        $gameId = $request->input('game_id');
        $csvFile = $request->file('csv_file');
    
        if (!$request->hasFile('csv_file')) {
            $currentDate = Carbon::now();
            $currentYear = $currentDate->year;
    
            // Loop for the years 2023 and 2024
            for ($year = 2023; $year <= $currentYear; $year++) {
                if ($year === $currentYear) {
                    // If it's the current year, add data only up to the current date
                    $endOfYear = $currentDate;
                } else {
                    // For past years, add data for the whole year
                    $endOfYear = Carbon::create($year)->endOfYear();
                }
    
                // Start from the beginning of the year
                $startOfYear = Carbon::create($year)->startOfYear();
    
                // Loop through each day of the year
                while ($startOfYear->lte($endOfYear)) {
                    OldResult::create([
                        'game_id'     => $gameId,
                        'result'      => rand(1, 100),
                        'result_date' => $startOfYear->copy(),
                        'year'        => $year,
                    ]);
                    $startOfYear->addDay();
                }
            }
        } else {
            $path = $csvFile->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
    
            foreach ($csv as $row) {
                OldResult::create([
                    'game_id'     => $gameId,
                    'result_date' => Carbon::createFromFormat('d/m/Y', $row['result_date']),
                    'result'      => (int) $row['result'],
                    'year'        => (int) $row['year'],
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Dummy data inserted for the specified game_id for the past 2 years up to the current date.');
        
    }

    public function addResultStore(Request $request){
        $gameId = $request->input('game_id');
        $result_date = $request->input('result_date');
        $result = $request->input('result');

        $oldResult = OldResult::create([
            'game_id' => $gameId,
            'result_date' => $result_date,
            'result' => $result,
            'year' => Carbon::parse($result_date)->year,
        ]);

        return redirect()->back()->with('success', 'Result added successfully');

    }
    public function downloadResultOld(Request $request)
{
    $request->validate([
        'game_id' => 'required|exists:games,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $gameId = $request->input('game_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $results = OldResult::where('game_id', $gameId)
        ->whereBetween('result_date', [$startDate, $endDate])
        ->get(['result_date', 'result', 'year']);

    if ($results->isEmpty()) {
        return redirect()->back()->with('error', 'No results found for the selected criteria.');
    }

    $fileName = "game_results_{$gameId}_{$startDate}_to_{$endDate}.csv";

    return response()->streamDownload(function () use ($results) {
        $handle = fopen('php://output', 'w');
        // Write the CSV headers
        fputcsv($handle, ['result_date', 'result', 'year']);
        // Write each result row
        foreach ($results as $result) {
            fputcsv($handle, [$result->result_date, $result->result, $result->year]);
        }
        fclose($handle);
    }, $fileName, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ]);
}

    public function bulkResultEdit($id)
    {
        $oldResult = OldResult::find($id);
        return view('admin.result.bulk-result-edit', compact('oldResult'));
    }

    public function bulkResultUpdate(Request $request, $id)
    {
        $oldResult = OldResult::find($id);
        $oldResult->update($request->all());
        return redirect()->back()->with('success', 'Result updated successfully');
    }

    public function bulkResultDelete($id){
        $oldResult = OldResult::find($id);
        $oldResult->delete();
        return redirect()->back()->with('success', 'Result deleted successfully');
    }
}
