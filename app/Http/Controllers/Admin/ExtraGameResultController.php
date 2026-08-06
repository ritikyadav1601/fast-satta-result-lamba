<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraGame;
use App\Models\ExtraGameResult;
use Carbon\Carbon;
use League\Csv\Reader;

class ExtraGameResultController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date');
        $games = ExtraGame::all();
        $oldResult = ExtraGameResult::orderBy('result_date', 'desc')->paginate(10);
        if($date){
            $oldResult = ExtraGameResult::where('result_date', $date)->orderBy('result_date', 'desc')->paginate(10);
        }
        foreach($oldResult as $result){
            $game = $games->where('id', $result->extra_game_id)->first();
            $result->game_name = $game ? $game->name : 'Unknown';
        }
        
        $lastSyncTime = ExtraGameResult::max('updated_at');
        
        return view('admin.extra-game-result.index', compact('games', 'oldResult', 'lastSyncTime'));
    }

    public function storeCsv(Request $request) 
    {
        $gameId = $request->input('extra_game_id');
        $csvFile = $request->file('csv_file');
    
        if ($request->hasFile('csv_file')) {
            $path = $csvFile->getRealPath();
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
    
            foreach ($csv as $row) {
                ExtraGameResult::create([
                    'extra_game_id' => $gameId,
                    'result_date'   => Carbon::createFromFormat('d/m/Y', $row['result_date']),
                    'result'        => (int) $row['result'],
                    'year'          => (int) $row['year'],
                ]);
            }
            return redirect()->back()->with('success', 'CSV data imported successfully.');
        }
    
        return redirect()->back()->with('error', 'Please upload a CSV file.');
    }

    public function storeSingle(Request $request){
        $gameId = $request->input('extra_game_id');
        $result_date = $request->input('result_date');
        $result = $request->input('result');

        ExtraGameResult::create([
            'extra_game_id' => $gameId,
            'result_date'   => $result_date,
            'result'        => $result,
            'year'          => Carbon::parse($result_date)->year,
        ]);

        return redirect()->back()->with('success', 'Result added successfully');
    }

    public function edit($id)
    {
        $oldResult = ExtraGameResult::find($id);
        return view('admin.extra-game-result.edit', compact('oldResult'));
    }

    public function update(Request $request, $id)
    {
        $oldResult = ExtraGameResult::find($id);
        $oldResult->update($request->all());
        return redirect()->route('admin.extra-game-result')->with('success', 'Result updated successfully');
    }

    public function destroy($id){
        $oldResult = ExtraGameResult::find($id);
        $oldResult->delete();
        return redirect()->back()->with('success', 'Result deleted successfully');
    }

    public function syncToday()
    {
        set_time_limit(60); // Auto time-out of 60 seconds so it never hangs the server
        
        $badGames = ['HAT OPEN', 'HAT CLOSE', 'WHITE GOLD', 'INDIA KING', 'GURU MANGAL', 'SUPER MAX'];
        $abbrevGames = ['DESAWAR' => 'DSWR', 'FARIDABAD' => 'FRBD', 'GHAZIABAD' => 'GZBD', 'DELHI BAZAR' => 'DL BZ', 'SHRI GANESH' => 'SG', 'BADRINATH' => 'BADRI'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://satta-king-fast.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) {
            return redirect()->back()->with('error', 'Failed to fetch homepage from source. The server might be down.');
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $gameMap = [];
        $rows = $xpath->query("//tr[contains(@class, 'game-result')]");
        foreach ($rows as $row) {
            $nameNode = $xpath->query(".//h3[@class='game-name']", $row)->item(0);
            $linkNode = $xpath->query(".//h3[@class='game-link']/a", $row)->item(0);
            if ($nameNode && $linkNode) {
                $name = trim($nameNode->textContent);
                $url = $linkNode->getAttribute('href');
                $gameMap[\Illuminate\Support\Str::slug($name)] = $url;
            }
        }
        unset($dom, $xpath, $rows);

        $currentYear = date('Y');
        $currentMonthStr = date('m');
        $todayDayStr = date('d');
        $todayFullDate = date('Y-m-d');
        
        // Find games that ALREADY have today's result so we skip fetching their pages entirely!
        $gamesWithTodayResult = \Illuminate\Support\Facades\DB::table('extra_game_results')
            ->where('result_date', $todayFullDate)
            ->whereNotNull('result')
            ->pluck('extra_game_id')
            ->toArray();

        $extraGames = ExtraGame::whereNotIn('name', $badGames)
            ->whereNotIn('id', $gamesWithTodayResult) // ONLY fetch missing games!
            ->get();
            
        $inserted = 0;

        foreach ($extraGames as $game) {
            $slug = \Illuminate\Support\Str::slug($game->name);
            if (!isset($gameMap[$slug])) continue;

            $baseUrl = $gameMap[$slug];
            $url = "{$baseUrl}?month={$currentMonthStr}&year={$currentYear}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 sec max per request to keep it super lite
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $chartHtml = curl_exec($ch);
            curl_close($ch);

            if (!$chartHtml) continue;

            $cdom = new \DOMDocument();
            @$cdom->loadHTML($chartHtml);
            $cxpath = new \DOMXPath($cdom);

            $headers = $cxpath->query("//tr[@class='date-name']//th[@class='name']");
            $gameIndex = -1;
            $abbrev = $abbrevGames[$game->name] ?? null;
            
            foreach ($headers as $index => $header) {
                $hText = trim($header->textContent);
                if (\Illuminate\Support\Str::slug($hText) == $slug || ($abbrev && stripos($hText, $abbrev) !== false)) {
                    $gameIndex = $index;
                    break;
                }
            }
            
            if ($gameIndex === -1 && $headers->length > 0 && $abbrev) {
                $gameIndex = 0;
            } elseif ($gameIndex === -1 && $headers->length > 0) {
                foreach ($headers as $index => $header) {
                    if (stripos(trim($header->textContent), explode(' ', $game->name)[0]) !== false) {
                        $gameIndex = $index;
                        break;
                    }
                }
            }

            // Only look at today's row to save memory & time!
            $days = $cxpath->query("//tr[@class='day-number'][.//td[@class='day' and text()='{$todayDayStr}']]");
            if ($days->length > 0) {
                $dayRow = $days->item(0);
                $numbers = $cxpath->query(".//td[@class='number']", $dayRow);
                
                if ($numbers->length > 0 && $gameIndex !== -1 && $numbers->item($gameIndex)) {
                    $resultVal = trim($numbers->item($gameIndex)->textContent);
                    
                    if (is_numeric($resultVal)) {
                        \Illuminate\Support\Facades\DB::table('extra_game_results')->updateOrInsert(
                            ['extra_game_id' => $game->id, 'result_date' => $todayFullDate],
                            ['result' => $resultVal, 'year' => $currentYear, 'created_at' => now(), 'updated_at' => now()]
                        );
                        $inserted++;
                    }
                }
            }
            unset($cdom, $cxpath, $days, $headers);
        }

        return redirect()->back()->with('success', "Successfully synchronized today's results (fetched {$inserted} new records).");
    }
}
