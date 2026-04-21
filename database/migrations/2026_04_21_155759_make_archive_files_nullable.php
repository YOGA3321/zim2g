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
            $table->string('file_name')->nullable()->change();
            $table->string('google_drive_file_id')->nullable()->change();
            $table->text('google_drive_link')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('file_name')->nullable(false)->change();
            $table->string('google_drive_file_id')->nullable(false)->change();
            $table->text('google_drive_link')->nullable(false)->change();
        });
    }
};
