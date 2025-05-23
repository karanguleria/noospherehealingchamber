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
        Schema::table('user_sessions', function (Blueprint $table) {
            //
            $table->boolean('audio_enabled')->default(0);
            $table->string('healing_type')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('voice_recording_enabled')->default(0);;
            //$table->boolean('voice_recording_enabled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            //
        });
    }
};
