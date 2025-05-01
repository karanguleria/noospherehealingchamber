<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('practitioner_id')->after('email_verified_at')->nullable();
            $table->string('type_id')->after('email_verified_at')->nullable();
            $table->string('status_id')->after('email_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('type_id');
             $table->dropColumn('practitioner_id');
            $table->dropColumn('status_id');
        });
    }
}
