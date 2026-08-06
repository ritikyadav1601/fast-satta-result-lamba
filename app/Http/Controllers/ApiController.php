<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameResult;

class ApiController extends Controller
{
    public function getUpdatedResults()
    {
        $today = date('Y-m-d');
        $results = GameResult::whereDate('result_date', $today)->with('game')->get();
            return response()->json([
            'success' => true,
            'message' => 'Results retrieved successfully.',
            'data' => $results,
        ]);
    }
}
