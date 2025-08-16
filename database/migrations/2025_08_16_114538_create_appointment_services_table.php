<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointment_services', function (Blueprint $table) {
            $table->id(); // Primary key (auto increment, big integer)
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('services_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_services');
    }
};
