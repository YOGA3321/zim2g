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
        Schema::create('zi_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zi_area_id')->constrained()->onDelete('cascade');
            $table->string('code'); // e.g., 1.1, 1.2
            $table->text('name'); // e.g., Unit kerja telah membentuk tim...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zi_components');
    }
};
