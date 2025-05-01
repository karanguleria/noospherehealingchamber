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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('excess');
            $table->text('balance');
            $table->text('insufficiency');
            $table->text('element');
            // $table->foreign('element_id')->references('id')->on('elements')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            $table->text('bodypart');
            // $table->foreign('bodypart_id')->references('id')->on('body_parts')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            $table->text('type');
            $table->boolean('state')->default(1);
            $table->unsignedBigInteger('result_id');
            $table->foreign('result_id')->references('id')->on('results')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
