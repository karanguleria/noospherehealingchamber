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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('element_id');
            $table->foreign('element_id')->references('id')->on('elements')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('bodypart_id');
            $table->foreign('bodypart_id')->references('id')->on('body_parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('type')->default(0);
            $table->boolean('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
