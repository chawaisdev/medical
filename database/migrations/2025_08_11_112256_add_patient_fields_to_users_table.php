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
        Schema::table('users', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('name');
            $table->integer('age')->nullable()->after('father_name');
            $table->string('cnic', 20)->nullable()->after('age');
            $table->string('contact_number', 20)->nullable()->after('cnic');
            $table->text('address')->nullable()->after('contact_number');
            $table->string('mr_number')->nullable()->after('address'); // Patient ID / MR number
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'age',
                'cnic',
                'contact_number',
                'address',
                'mr_number',
            ]);
        });
    }
};
