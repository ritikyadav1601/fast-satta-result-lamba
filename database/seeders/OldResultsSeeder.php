<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OldResult;
use App\Models\Game;
use Carbon\Carbon;

class OldResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Get all games from the database
          $games = Game::all();

          // Loop through each game
          foreach ($games as $game) {
              // Start from today
              $currentDate = Carbon::now(); 
  
              // Loop through each of the past 4 years (including today)
              for ($i = 0; $i < 4; $i++) {
                  // Get the year for the current iteration
                  $year = $currentDate->year; 
  
                  // Loop through each day of the year (calculate number of days)
                  $daysInYear = $currentDate->isLeapYear() ? 366 : 365; // Check if it's a leap year
  
                  for ($day = 0; $day < $daysInYear; $day++) {
                      $date = $currentDate->copy()->subDays($day); // Go back day by day from current date
  
                      OldResult::create([
                          'game_id'     => $game->id,
                          'result'      => rand(1, 100), // Modify the range as needed
                          'result_date' => $date,        // Unique date
                          'year'        => $year,        // Year the result belongs to
                      ]);
                  }
  
                  // Move to the previous year
                  $currentDate = $currentDate->subYear(); 
              }
          }
    }
}
