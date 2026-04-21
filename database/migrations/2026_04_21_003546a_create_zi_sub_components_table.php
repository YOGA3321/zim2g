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
        Schema::create('zi_sub_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zi_component_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->text('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zi_sub_components');
    }
};
