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
        Schema::create('zi_areas', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., Area 1
            $table->string('name'); // e.g., Manajemen Perubahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zi_areas');
    }
};
