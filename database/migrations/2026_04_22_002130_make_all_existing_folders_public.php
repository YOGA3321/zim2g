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
        $driveService = new \App\Services\GoogleDriveService();
        if ($driveService->isReady()) {
            \App\Models\Archive::all()->each(function($archive) use ($driveService) {
                if ($archive->google_drive_folder_id) {
                    $driveService->setPublic($archive->google_drive_folder_id);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            //
        });
    }
};
