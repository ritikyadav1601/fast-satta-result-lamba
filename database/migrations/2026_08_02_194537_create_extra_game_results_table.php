<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extra_game_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extra_game_id');
            $table->integer('result');
            $table->date('result_date');
            $table->integer('year');
            $table->timestamps();
            
            $table->foreign('extra_game_id')->references('id')->on('extra_games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_game_results');
    }
};
