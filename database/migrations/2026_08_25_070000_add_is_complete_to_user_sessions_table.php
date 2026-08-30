<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('user_sessions', 'is_complete')) {
                $table->boolean('is_complete')->default(0)->after('session_end');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('user_sessions', 'is_complete')) {
                $table->dropColumn('is_complete');
            }
        });
    }
};
