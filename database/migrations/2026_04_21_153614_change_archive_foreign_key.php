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
        Schema::table('archives', function (Blueprint $table) {
            // Drop old foreign key and column
            $table->dropForeign(['zi_sub_component_id']);
            $table->dropColumn('zi_sub_component_id');
            
            // Add new column pointing to ZiComponent
            $table->foreignId('zi_component_id')->after('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropForeign(['zi_component_id']);
            $table->dropColumn('zi_component_id');
            $table->foreignId('zi_sub_component_id')->after('user_id')->constrained()->onDelete('cascade');
        });
    }
};
