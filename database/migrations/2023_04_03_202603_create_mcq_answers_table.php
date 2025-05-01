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
        Schema::create('mcq_answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer')->nullable();
            $table->text('element')->nullable();
            $table->text('bodypart')->nullable();
            $table->text('type')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('result_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_answers');
    }
};
