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
        Schema::create('archive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained()->onDelete('cascade');
            $table->string('file_name');
            $table->string('google_drive_file_id');
            $table->text('google_drive_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_files');
    }
};
