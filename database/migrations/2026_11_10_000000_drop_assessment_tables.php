<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('mcq_answers');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('results');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('bodyparts');
        Schema::dropIfExists('elements');
    }

    public function down(): void
    {
        // Intentionally empty — removed feature tables are not recreated.
    }
};
