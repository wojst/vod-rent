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
        Schema::create('movies_actors_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('movie_id')->on('movies');
            $table->unsignedBigInteger('actor_id');
            $table->foreign('actor_id')->references('actor_id')->on('actors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies_actors_transfers');
    }
};
