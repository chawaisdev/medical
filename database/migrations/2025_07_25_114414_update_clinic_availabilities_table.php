<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clinic_availabilities', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('end_time');
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('clinic_availabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dropColumn('is_active');
        });
    }

};
